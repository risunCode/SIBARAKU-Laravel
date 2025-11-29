<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:categories.view', only: ['index', 'show']),
            new Middleware('permission:categories.create', only: ['create', 'store']),
            new Middleware('permission:categories.edit', only: ['edit', 'update']),
            new Middleware('permission:categories.delete', only: ['destroy']),
        ];
    }

    /**
     * Tampilkan daftar kategori.
     */
    public function index(Request $request): View
    {
        $query = Category::withCount('commodities')
            ->with('parent');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by parent
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'code');
        $direction = $request->get('direction', 'asc');

        switch ($sort) {
            case 'commodities_count':
                $query->orderBy('commodities_count', $direction);
                break;
            case 'parent_name':
                $query->select('categories.*')
                      ->leftJoin('categories as parent', 'categories.parent_id', '=', 'parent.id')
                      ->withCount('commodities')
                      ->orderBy('parent.name', $direction)
                      ->orderBy('categories.name', 'asc');
                break;
            default:
                $query->orderBy('code', $direction);
        }

        $perPage = min($request->get('per_page', 15), 100); // Max 100 per page
        $categories = $query->paginate($perPage)->withQueryString();
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        $existingCodes = Category::whereNotNull('code')->distinct()->pluck('code')->sort();

        return view('categories.index', compact('categories', 'parentCategories', 'existingCodes'));
    }

    /**
     * Tampilkan form tambah kategori.
     */
    public function create(): View
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string'],
            'new_code' => ['nullable', 'string', 'max:10', 'unique:categories,code', 'required_if:code,new'],
            'parent_id' => ['nullable', 'string'],
            'new_parent_name' => ['nullable', 'string', 'max:255', 'required_if:parent_id,new'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable'],
        ]);

        // Handle code selection
        if ($validated['code'] === 'new') {
            $validated['code'] = $validated['new_code'];
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        // Handle new parent category creation
        $category = null;
        if ($validated['parent_id'] === 'new') {
            // Check if parent category already exists before transaction
            $existingParent = Category::where('name', $validated['new_parent_name'])
                ->where('parent_id', null)
                ->first();
            
            try {
                // Use transaction to ensure both parent and child are created together
                $category = DB::transaction(function () use ($validated, $existingParent) {
                    if ($existingParent) {
                        // Use existing parent category
                        $parentCategory = $existingParent;
                    } else {
                        // Create new parent category first
                        $parentData = [
                            'name' => $validated['new_parent_name'],
                            'code' => strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['new_parent_name']), 0, 3)) . rand(100, 999),
                            'parent_id' => null,
                            'description' => 'Parent kategori otomatis dibuat',
                            'is_active' => true,
                        ];
                        
                        // Ensure parent code is unique
                        while (Category::where('code', $parentData['code'])->exists()) {
                            $parentData['code'] = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['new_parent_name']), 0, 3)) . rand(100, 999);
                        }
                        
                        $parentCategory = Category::create($parentData);
                    }
                    
                    // Ensure child code is unique (recheck in transaction)
                    while (Category::where('code', $validated['code'])->exists()) {
                        $validated['code'] = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name']), 0, 3)) . rand(100, 999);
                    }
                    
                    // Create child category with the parent ID
                    $childData = [
                        'name' => $validated['name'],
                        'code' => $validated['code'],
                        'parent_id' => $parentCategory->id,
                        'description' => $validated['description'],
                        'is_active' => $validated['is_active'],
                    ];
                    
                    return Category::create($childData);
                });
                
                $message = 'Kategori berhasil ditambahkan. ' . 
                    ($existingParent ? 'Menggunakan parent kategori yang sudah ada.' : 'Parent kategori baru juga dibuat.');
                
            } catch (\Exception $e) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Gagal membuat kategori: ' . $e->getMessage()], 500);
                }
                return back()->withErrors(['error' => 'Gagal membuat kategori: ' . $e->getMessage()])->withInput();
            }
        } else {
            // Normal category creation
            try {
                $validated['parent_id'] = $validated['parent_id'] ?: null;
                
                // Ensure code is unique
                while (Category::where('code', $validated['code'])->exists()) {
                    $validated['code'] = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name']), 0, 3)) . rand(100, 999);
                }
                
                $category = Category::create($validated);
                $message = 'Kategori berhasil ditambahkan.';
            } catch (\Exception $e) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Gagal membuat kategori: ' . $e->getMessage()], 500);
                }
                return back()->withErrors(['error' => 'Gagal membuat kategori: ' . $e->getMessage()])->withInput();
            }
        }

        // Activity logged;

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan.']);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kategori.
     */
    public function show(Category $category): View
    {
        $category->loadCount('commodities');
        $category->load(['parent', 'children']);
        return view('categories.show', compact('category'));
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit(Category $category): View
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update kategori.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:categories,code,' . $category->id],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable'],
        ]);

        // Jangan bisa jadikan diri sendiri sebagai parent
        if ($validated['parent_id'] == $category->id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Kategori tidak bisa menjadi parent dirinya sendiri.'], 422);
            }
            return back()->withErrors(['parent_id' => 'Kategori tidak bisa menjadi parent dirinya sendiri.']);
        }

        // For update: unchecked checkbox means false (not default true like create)
        $validated['is_active'] = $request->boolean('is_active');

        $oldValues = $category->toArray();
        $category->update($validated);

        // Activity logged;

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui.']);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(Request $request, Category $category)
    {
        // Cek apakah punya barang
        if ($category->commodities()->exists()) {
            $errorMsg = 'Kategori tidak bisa dihapus karena masih memiliki barang.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return back()->with('error', $errorMsg);
        }

        // Cek apakah punya sub-kategori
        if ($category->children()->exists()) {
            $errorMsg = 'Kategori tidak bisa dihapus karena masih memiliki sub-kategori.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return back()->with('error', $errorMsg);
        }

        $categoryName = $category->name;
        $category->delete();

        // Activity logged;

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
