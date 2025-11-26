<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\CommodityImage;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommodityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:commodities.view', only: ['index', 'show']),
            new Middleware('permission:commodities.create', only: ['create', 'store']),
            new Middleware('permission:commodities.edit', only: ['edit', 'update']),
            new Middleware('permission:commodities.delete', only: ['destroy']),
            new Middleware('permission:commodities.export', only: ['export']),
        ];
    }

    /**
     * Tampilkan daftar barang.
     */
    public function index(Request $request): View
    {
        $query = Commodity::with(['category', 'location', 'primaryImage']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $commodities = $query->paginate(15)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('commodities.index', compact('commodities', 'categories', 'locations'));
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('commodities.create', compact('categories', 'locations'));
    }

    /**
     * Simpan barang baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'acquisition_type' => ['required', 'in:pembelian,hibah,bantuan,produksi,lainnya'],
            'acquisition_source' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'condition' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'purchase_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'specifications' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'images.*' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['created_by'] = Auth::id();
        $validated['purchase_price'] = $validated['purchase_price'] ?? 0;

        $commodity = Commodity::create($validated);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('commodities', 'public');
                $commodity->images()->create([
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        ActivityLog::log('created', "Menambah barang: {$commodity->name} ({$commodity->item_code})", $commodity);

        return redirect()->route('commodities.show', $commodity)
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail barang.
     */
    public function show(Commodity $commodity): View
    {
        $commodity->load([
            'category',
            'location',
            'images',
            'creator',
            'updater',
            'transfers.fromLocation',
            'transfers.toLocation',
            'maintenanceLogs',
            'disposals',
        ]);

        return view('commodities.show', compact('commodity'));
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Commodity $commodity): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();
        $commodity->load('images');

        return view('commodities.edit', compact('commodity', 'categories', 'locations'));
    }

    /**
     * Update barang.
     */
    public function update(Request $request, Commodity $commodity): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'acquisition_type' => ['required', 'in:pembelian,hibah,bantuan,produksi,lainnya'],
            'acquisition_source' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'condition' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'purchase_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'specifications' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['exists:commodity_images,id'],
            'primary_image' => ['nullable', 'exists:commodity_images,id'],
        ]);

        $validated['updated_by'] = Auth::id();
        $validated['purchase_price'] = $validated['purchase_price'] ?? 0;

        $oldValues = $commodity->toArray();

        // Delete selected images
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = CommodityImage::find($imageId);
                if ($image && $image->commodity_id === $commodity->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Handle new images
        if ($request->hasFile('images')) {
            $lastOrder = $commodity->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('commodities', 'public');
                $commodity->images()->create([
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'is_primary' => false,
                    'sort_order' => $lastOrder + $index + 1,
                ]);
            }
        }

        // Set primary image
        if ($request->filled('primary_image')) {
            $commodity->images()->update(['is_primary' => false]);
            $commodity->images()->where('id', $request->primary_image)->update(['is_primary' => true]);
        }

        unset($validated['images'], $validated['delete_images'], $validated['primary_image']);
        $commodity->update($validated);

        ActivityLog::log('updated', "Mengubah barang: {$commodity->name} ({$commodity->item_code})", $commodity, $oldValues, $commodity->fresh()->toArray());

        return redirect()->route('commodities.show', $commodity)
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus barang.
     */
    public function destroy(Commodity $commodity): RedirectResponse
    {
        // Cek apakah punya transfer pending
        if ($commodity->transfers()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Barang tidak bisa dihapus karena masih memiliki transfer yang belum selesai.');
        }

        // Soft delete (gambar tidak dihapus)
        $commodity->delete();

        ActivityLog::log('deleted', "Menghapus barang: {$commodity->name} ({$commodity->item_code})", $commodity);

        return redirect()->route('commodities.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Export daftar barang ke PDF.
     */
    public function export(Request $request)
    {
        $query = Commodity::with(['category', 'location']);

        // Apply same filters as index
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $commodities = $query->orderBy('name')->get();

        $pdf = Pdf::loadView('reports.pdf.inventory', [
            'commodities' => $commodities,
            'title' => 'Daftar Inventaris Barang',
            'date' => now()->format('d F Y'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('inventaris-barang-' . now()->format('Y-m-d') . '.pdf');
    }
}
