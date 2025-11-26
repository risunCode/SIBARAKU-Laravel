<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Commodities
            'commodities.view',
            'commodities.create',
            'commodities.edit',
            'commodities.delete',
            'commodities.export',
            'commodities.import',

            // Categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Locations
            'locations.view',
            'locations.create',
            'locations.edit',
            'locations.delete',

            // Transfers
            'transfers.view',
            'transfers.create',
            'transfers.approve',
            'transfers.reject',

            // Maintenance
            'maintenance.view',
            'maintenance.create',
            'maintenance.edit',
            'maintenance.delete',

            // Disposals
            'disposals.view',
            'disposals.create',
            'disposals.approve',
            'disposals.reject',

            // Reports
            'reports.view',
            'reports.export',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Settings
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Buat roles dan assign permissions
        // Super Admin - akses penuh (max 3 orang termasuk admin)
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - hampir semua kecuali settings (max 3 orang total dengan super-admin)
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'dashboard.view',
            'commodities.view', 'commodities.create', 'commodities.edit', 'commodities.delete', 'commodities.export', 'commodities.import',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'locations.view', 'locations.create', 'locations.edit', 'locations.delete',
            'transfers.view', 'transfers.create', 'transfers.approve', 'transfers.reject',
            'maintenance.view', 'maintenance.create', 'maintenance.edit', 'maintenance.delete',
            'disposals.view', 'disposals.create', 'disposals.approve', 'disposals.reject',
            'reports.view', 'reports.export',
            'users.view', 'users.create', 'users.edit',
        ]);

        // Staff - operasional (unlimited)
        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'dashboard.view',
            'commodities.view', 'commodities.create', 'commodities.edit',
            'categories.view',
            'locations.view',
            'transfers.view', 'transfers.create',
            'maintenance.view', 'maintenance.create',
            'disposals.view', 'disposals.create',
            'reports.view',
        ]);
    }
}
