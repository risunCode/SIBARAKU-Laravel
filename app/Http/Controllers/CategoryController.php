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

        $perPage = min($request->get('per_page', 15), 100); // Max 100 per page
        $categories = $query->orderBy('name')->paginate($perPage)->withQueryString();
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('categories.index', compact('categories', 'parentCategories'));
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
            'code' => ['nullable', 'string', 'max:10', 'unique:categories,code'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        // Auto generate code if empty
        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name']), 0, 3)) . rand(100, 999);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $category = Category::create($validated);

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
            'is_active' => ['boolean'],
        ]);

        // Jangan bisa jadikan diri sendiri sebagai parent
        if ($validated['parent_id'] == $category->id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Kategori tidak bisa menjadi parent dirinya sendiri.'], 422);
            }
            return back()->withErrors(['parent_id' => 'Kategori tidak bisa menjadi parent dirinya sendiri.']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

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
