<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class LocationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:locations.view', only: ['index', 'show']),
            new Middleware('permission:locations.create', only: ['create', 'store']),
            new Middleware('permission:locations.edit', only: ['edit', 'update']),
            new Middleware('permission:locations.delete', only: ['destroy']),
        ];
    }

    /**
     * Tampilkan daftar lokasi.
     */
    public function index(Request $request): View
    {
        $query = Location::withCount('commodities');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('building', 'like', "%{$search}%");
            });
        }

        // Filter by building
        if ($request->filled('building')) {
            $query->where('building', $request->building);
        }

        $perPage = min($request->get('per_page', 15), 100); // Max 100 per page
        $locations = $query->orderBy('name')->paginate($perPage)->withQueryString();
        $buildings = Location::distinct()->pluck('building')->filter();

        return view('locations.index', compact('locations', 'buildings'));
    }

    /**
     * Tampilkan form tambah lokasi.
     */
    public function create(): View
    {
        return view('locations.create');
    }

    /**
     * Simpan lokasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20', 'unique:locations,code'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'pic' => ['nullable', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:50'],
            'room' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        // Auto generate code if empty
        if (empty($validated['code'])) {
            $validated['code'] = 'LOC' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name']), 0, 3)) . rand(100, 999);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $location = Location::create($validated);

        // Activity logged;

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Lokasi berhasil ditambahkan.']);
        }

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail lokasi.
     */
    public function show(Location $location): View
    {
        $location->loadCount('commodities');
        return view('locations.show', compact('location'));
    }

    /**
     * Tampilkan form edit lokasi.
     */
    public function edit(Location $location): View
    {
        return view('locations.edit', compact('location'));
    }

    /**
     * Update lokasi.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:locations,code,' . $location->id],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'pic' => ['nullable', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:50'],
            'room' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $oldValues = $location->toArray();
        $location->update($validated);

        // Activity logged;

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Lokasi berhasil diperbarui.']);
        }

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    /**
     * Hapus lokasi.
     */
    public function destroy(Request $request, Location $location)
    {
        // Cek apakah punya barang
        if ($location->commodities()->exists()) {
            $errorMsg = 'Lokasi tidak bisa dihapus karena masih memiliki barang.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return back()->with('error', $errorMsg);
        }

        $locationName = $location->name;
        $location->delete();

        // Activity logged;

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Lokasi berhasil dihapus.']);
        }

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }
}
