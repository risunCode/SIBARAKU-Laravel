<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class FixReferralPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral:fix-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix referral permissions for admin roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Assigning users.manage permission to admin roles...');

        $roles = Role::whereIn('name', ['super-admin', 'admin'])->get();

        foreach ($roles as $role) {
            $role->givePermissionTo('users.manage');
            $this->info("Assigned users.manage to {$role->name}");
        }

        $this->info('Done!');
    }
}
