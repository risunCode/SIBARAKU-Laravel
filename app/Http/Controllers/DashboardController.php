<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Commodity;
use App\Models\Disposal;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\Transfer;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dashboard.view'),
        ];
    }

    /**
     * Tampilkan dashboard.
     */
    public function index(): View
    {
        // Statistik utama
        $stats = [
            'total_commodities' => Commodity::count(),
            'total_categories' => Category::count(),
            'total_locations' => Location::count(),
            'total_value' => Commodity::sum('purchase_price'),
            'transfers_this_month' => Transfer::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Statistik kondisi barang
        $conditionStats = [
            'baik' => Commodity::where('condition', 'baik')->count(),
            'rusak_ringan' => Commodity::where('condition', 'rusak_ringan')->count(),
            'rusak_berat' => Commodity::where('condition', 'rusak_berat')->count(),
        ];

        // Pending approvals
        $pendingTransfers = Transfer::where('status', 'pending')->count();
        $pendingDisposals = Disposal::where('status', 'pending')->count();

        // Upcoming maintenance
        $upcomingMaintenance = Maintenance::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->where('next_maintenance_date', '<=', now()->addDays(30))
            ->count();

        // Overdue maintenance
        $overdueMaintenance = Maintenance::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<', now())
            ->count();

        // Barang terbaru
        $recentCommodities = Commodity::withRelations()
            ->latest()
            ->limit(5)
            ->get();

        // Transfer terbaru
        $recentTransfers = Transfer::with(['commodity', 'fromLocation', 'toLocation', 'requester'])
            ->latest()
            ->limit(5)
            ->get();

        // Aktivitas terbaru (exclude login activities)
        $recentActivities = \App\Models\ActivityLog::with('user')
            ->where('action', '!=', 'login')
            ->latest()
            ->limit(10)
            ->get();

        // Barang per kategori (untuk chart)
        $commoditiesByCategory = Category::withCount('commodities')
            ->orderByDesc('commodities_count')
            ->limit(5)
            ->get();

        // Barang per lokasi (untuk chart)
        $commoditiesByLocation = Location::withCount('commodities')
            ->orderByDesc('commodities_count')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'conditionStats',
            'pendingTransfers',
            'pendingDisposals',
            'upcomingMaintenance',
            'overdueMaintenance',
            'recentCommodities',
            'recentTransfers',
            'recentActivities',
            'commoditiesByCategory',
            'commoditiesByLocation'
        ));
    }
}
