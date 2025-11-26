<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReferralCodeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.manage'),
        ];
    }

    /**
     * Display a listing of referral codes.
     */
    public function index(Request $request)
    {
        $query = ReferralCode::with('creator')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                      });
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        $referralCodes = $query->paginate($request->get('per_page', 10));

        $stats = [
            'total' => ReferralCode::count(),
            'active' => ReferralCode::where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })->count(),
            'total_used' => ReferralCode::sum('used_count'),
        ];

        return view('referral-codes.index', compact('referralCodes', 'stats'));
    }

    /**
     * Store a newly created referral code.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:20|unique:referral_codes,code',
            'description' => 'nullable|string|max:255',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $referralCode = ReferralCode::create([
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'max_uses' => $validated['max_uses'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kode referral berhasil dibuat!',
            'data' => $referralCode,
        ]);
    }

    /**
     * Update the specified referral code.
     */
    public function update(Request $request, ReferralCode $referralCode): JsonResponse
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $referralCode->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kode referral berhasil diperbarui!',
            'data' => $referralCode->fresh(),
        ]);
    }

    /**
     * Toggle referral code status.
     */
    public function toggle(ReferralCode $referralCode): JsonResponse
    {
        $referralCode->update([
            'is_active' => !$referralCode->is_active,
        ]);

        $status = $referralCode->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Kode referral berhasil {$status}!",
            'data' => $referralCode->fresh(),
        ]);
    }

    /**
     * Remove the specified referral code.
     */
    public function destroy(ReferralCode $referralCode): JsonResponse
    {
        $referralCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode referral berhasil dihapus!',
        ]);
    }

    /**
     * Generate a new code (AJAX).
     */
    public function generate(): JsonResponse
    {
        return response()->json([
            'code' => ReferralCode::generateUniqueCode(),
        ]);
    }
}
