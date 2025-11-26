<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Commodity;
use App\Models\Location;
use App\Models\Transfer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransferController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:transfers.view', only: ['index', 'show']),
            new Middleware('permission:transfers.create', only: ['create', 'store']),
            new Middleware('permission:transfers.approve', only: ['approve', 'reject', 'complete']),
        ];
    }

    /**
     * Tampilkan daftar transfer.
     */
    public function index(Request $request): View
    {
        $query = Transfer::with(['commodity', 'fromLocation', 'toLocation', 'requester', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transfer_number', 'like', "%{$search}%")
                    ->orWhereHas('commodity', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $perPage = $request->input('per_page', 15);
        if ($perPage == 'all' || $perPage > 500) {
            $perPage = $query->count();
        }
        $transfers = $query->latest()->paginate($perPage)->withQueryString();
        
        // Data untuk modal
        $commodities = Commodity::with('location')->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('transfers.index', compact('transfers', 'commodities', 'locations'));
    }

    /**
     * Tampilkan form transfer baru.
     */
    public function create(Request $request): View
    {
        $commodities = Commodity::with('location')->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();
        $selectedCommodity = $request->commodity_id ? Commodity::find($request->commodity_id) : null;

        return view('transfers.create', compact('commodities', 'locations', 'selectedCommodity'));
    }

    /**
     * Simpan transfer baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commodity_id' => ['required', 'exists:commodities,id'],
            'to_location_id' => ['required', 'exists:locations,id'],
            'reason' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $commodity = Commodity::findOrFail($validated['commodity_id']);

        // Validasi: lokasi tujuan harus berbeda
        if ($commodity->location_id == $validated['to_location_id']) {
            return back()->withErrors(['to_location_id' => 'Lokasi tujuan harus berbeda dengan lokasi saat ini.']);
        }

        $transfer = Transfer::create([
            'commodity_id' => $validated['commodity_id'],
            'from_location_id' => $commodity->location_id,
            'to_location_id' => $validated['to_location_id'],
            'requested_by' => Auth::id(),
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        ActivityLog::log('created', "Mengajukan transfer: {$transfer->transfer_number}", $transfer);

        return redirect()->route('transfers.show', $transfer)
            ->with('success', 'Pengajuan transfer berhasil dibuat.');
    }

    /**
     * Tampilkan detail transfer.
     */
    public function show(Transfer $transfer): View
    {
        $transfer->load(['commodity.images', 'fromLocation', 'toLocation', 'requester', 'approver']);
        return view('transfers.show', compact('transfer'));
    }

    /**
     * Setujui transfer.
     */
    public function approve(Transfer $transfer): RedirectResponse
    {
        if (!$transfer->canBeApproved()) {
            return back()->with('error', 'Transfer tidak bisa disetujui.');
        }

        $transfer->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        ActivityLog::log('approved', "Menyetujui transfer: {$transfer->transfer_number}", $transfer);

        return back()->with('success', 'Transfer berhasil disetujui.');
    }

    /**
     * Tolak transfer.
     */
    public function reject(Request $request, Transfer $transfer): RedirectResponse
    {
        if (!$transfer->canBeRejected()) {
            return back()->with('error', 'Transfer tidak bisa ditolak.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $transfer->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        ActivityLog::log('rejected', "Menolak transfer: {$transfer->transfer_number}", $transfer);

        return back()->with('success', 'Transfer berhasil ditolak.');
    }

    /**
     * Selesaikan transfer (update lokasi barang).
     */
    public function complete(Transfer $transfer): RedirectResponse
    {
        if (!$transfer->canBeCompleted()) {
            return back()->with('error', 'Transfer tidak bisa diselesaikan.');
        }

        // Update lokasi barang
        $transfer->commodity->update([
            'location_id' => $transfer->to_location_id,
        ]);

        $transfer->update([
            'status' => 'completed',
            'transfer_date' => now(),
        ]);

        ActivityLog::log('transferred', "Menyelesaikan transfer: {$transfer->transfer_number}", $transfer);

        return back()->with('success', 'Transfer berhasil diselesaikan. Lokasi barang telah diperbarui.');
    }

    /**
     * Hapus transfer (hanya pending).
     */
    public function destroy(Transfer $transfer): RedirectResponse
    {
        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Hanya transfer dengan status pending yang bisa dibatalkan.');
        }

        if ($transfer->requested_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk membatalkan transfer ini.');
        }

        $transfer->update(['status' => 'cancelled']);

        ActivityLog::log('deleted', "Membatalkan transfer: {$transfer->transfer_number}", $transfer);

        return redirect()->route('transfers.index')
            ->with('success', 'Transfer berhasil dibatalkan.');
    }
}
