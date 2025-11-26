# Implementasi Sistem Role & Permission
## Inventaris Barang - Complete Guide

---

## 1. Overview Sistem

### Konsep Dasar
- **RBAC (Role-Based Access Control)**: User diberi role, role punya permissions
- **Package**: Spatie Laravel Permission
- **Granularity**: Permission-level control untuk setiap fitur
- **Flexibility**: Support multiple roles per user

### Hierarki User
```
Super Admin (ID: 1)
    ├── Admin (ID: 2)
    │   ├── Manager (ID: 3)
    │   │   └── Staff (ID: 4)
    │   └── Viewer (ID: 5)
    └── Guest (ID: 6)
```

---

## 2. Database Schema

### Tables Structure (Spatie Standard)

#### `roles`
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)     -- admin, manager, staff, viewer
guard_name          VARCHAR(255)     -- web, api
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE(name, guard_name)
```

#### `permissions`
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)     -- view-commodities, create-commodities
guard_name          VARCHAR(255)     -- web, api
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE(name, guard_name)
```

#### `model_has_roles` (User-Role Pivot)
```sql
role_id             BIGINT UNSIGNED  -- FK to roles.id
model_type          VARCHAR(255)     -- App\Models\User
model_id            BIGINT UNSIGNED  -- User ID
team_foreign_key    BIGINT UNSIGNED NULL

PRIMARY KEY(role_id, model_id, model_type)
FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE CASCADE
```

#### `model_has_permissions` (Direct User Permissions)
```sql
permission_id       BIGINT UNSIGNED  -- FK to permissions.id
model_type          VARCHAR(255)     -- App\Models\User
model_id            BIGINT UNSIGNED  -- User ID
team_foreign_key    BIGINT UNSIGNED NULL

PRIMARY KEY(permission_id, model_id, model_type)
FOREIGN KEY(permission_id) REFERENCES permissions(id) ON DELETE CASCADE
```

#### `role_has_permissions` (Role-Permission Pivot)
```sql
permission_id       BIGINT UNSIGNED  -- FK to permissions.id
role_id             BIGINT UNSIGNED  -- FK to roles.id

PRIMARY KEY(permission_id, role_id)
FOREIGN KEY(permission_id) REFERENCES permissions(id) ON DELETE CASCADE
FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE CASCADE
```

---

## 3. Roles Definition

### Role Matrix

| Role | Level | Description | Access Scope |
|------|-------|-------------|--------------|
| **Super Admin** | 1 | System owner | Full system access + config |
| **Admin** | 2 | System manager | All CRUD + reports, no config |
| **Manager** | 3 | Department head | Manage inventory in department |
| **Staff** | 4 | Regular employee | Create, view, limited edit |
| **Viewer** | 5 | Read-only user | View data only |
| **Guest** | 6 | Public access | Dashboard only |

### Role Details

#### 1. Super Admin
```yaml
name: super-admin
permissions: ALL (wildcard bypass)
capabilities:
  - System configuration
  - User & role management
  - Database operations
  - Backup & restore
  - Audit log access
  - Delete any records
special: Can override all permissions
```

#### 2. Admin
```yaml
name: admin
permissions:
  - commodities.*
  - locations.*
  - acquisitions.*
  - users.view, users.create, users.edit
  - roles.view, roles.assign
  - reports.*
  - exports.*
capabilities:
  - Full inventory management
  - User management (except super-admin)
  - Generate all reports
  - Approve transfers
  - Manage categories
restrictions:
  - Cannot modify super-admin
  - Cannot change system config
  - Cannot access audit logs
```

#### 3. Manager
```yaml
name: manager
permissions:
  - commodities.view, commodities.create, commodities.edit
  - commodities.approve-disposal
  - locations.view, locations.create
  - acquisitions.view
  - reports.view, reports.department
  - transfers.create, transfers.approve
capabilities:
  - Manage inventory in assigned department
  - Approve item transfers
  - Generate department reports
  - Add new items
restrictions:
  - Cannot delete items permanently
  - Cannot manage users
  - Cannot access other departments (if multi-tenant)
```

#### 4. Staff
```yaml
name: staff
permissions:
  - commodities.view, commodities.create
  - commodities.edit-assigned
  - locations.view
  - acquisitions.view
  - transfers.request
capabilities:
  - View all inventory
  - Add new items
  - Edit items assigned to them
  - Request transfers
  - View basic reports
restrictions:
  - Cannot delete items
  - Cannot approve anything
  - Cannot export data
  - Cannot access sensitive info
```

#### 5. Viewer
```yaml
name: viewer
permissions:
  - commodities.view
  - locations.view
  - acquisitions.view
  - reports.view-basic
capabilities:
  - Read-only access
  - View inventory lists
  - View item details
  - View basic reports
restrictions:
  - No write access
  - No exports
  - No sensitive data
```

#### 6. Guest
```yaml
name: guest
permissions:
  - dashboard.view
capabilities:
  - View dashboard only
  - Public statistics
restrictions:
  - No inventory access
  - No user info
```

---

## 4. Permissions List

### Permission Naming Convention
Format: `resource.action`
Example: `commodities.create`, `users.delete`

### Complete Permission List

#### Commodities (Inventory Items)
```
commodities.view              - View list & details
commodities.view-all          - View across all departments
commodities.view-assigned     - View only assigned items
commodities.create            - Add new items
commodities.edit              - Edit any item
commodities.edit-assigned     - Edit only assigned items
commodities.delete            - Soft delete
commodities.force-delete      - Permanent delete
commodities.restore           - Restore soft-deleted
commodities.approve-disposal  - Approve write-offs
commodities.import            - Bulk import
commodities.export            - Export to Excel/PDF
```

#### Locations
```
locations.view                - View locations
locations.create              - Add new location
locations.edit                - Edit location
locations.delete              - Delete location
locations.assign-items        - Assign items to location
```

#### Acquisitions (Procurement Methods)
```
acquisitions.view             - View methods
acquisitions.create           - Add method
acquisitions.edit             - Edit method
acquisitions.delete           - Delete method
```

#### Categories
```
categories.view               - View categories
categories.create             - Add category
categories.edit               - Edit category
categories.delete             - Delete category
categories.manage-hierarchy   - Manage parent-child
```

#### Transfers
```
transfers.view                - View transfer history
transfers.create              - Create transfer request
transfers.approve             - Approve transfer
transfers.reject              - Reject transfer
transfers.cancel              - Cancel pending transfer
```

#### Maintenance
```
maintenance.view              - View maintenance records
maintenance.create            - Log maintenance
maintenance.schedule          - Schedule maintenance
maintenance.approve           - Approve maintenance cost
```

#### Reports
```
reports.view-basic            - Basic statistics
reports.view-detailed         - Detailed analytics
reports.department            - Department-specific
reports.financial             - Financial reports
reports.depreciation          - Depreciation reports
reports.export                - Export reports
```

#### Users
```
users.view                    - View user list
users.view-profile            - View user profiles
users.create                  - Create users
users.edit                    - Edit users
users.delete                  - Delete users
users.ban                     - Ban/suspend users
users.reset-password          - Reset passwords
```

#### Roles & Permissions
```
roles.view                    - View roles
roles.create                  - Create roles
roles.edit                    - Edit roles
roles.delete                  - Delete roles
roles.assign                  - Assign roles to users
permissions.view              - View permissions
permissions.assign            - Assign permissions
```

#### System
```
system.settings               - Access settings
system.backup                 - Create backups
system.logs                   - View system logs
system.audit                  - View audit trails
system.maintenance-mode       - Toggle maintenance
```

---

## 5. Implementation Flow

### Step 1: Installation
```bash
composer require spatie/laravel-permission

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

php artisan migrate
```

### Step 2: Configuration
File: `config/permission.php`
```php
return [
    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],
    
    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],
    
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
    ],
];
```

### Step 3: User Model Setup
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    
    protected $guard_name = 'web';
}
```

### Step 4: Create Seeders

#### RoleSeeder.php
```php
$roles = [
    ['name' => 'super-admin', 'guard_name' => 'web'],
    ['name' => 'admin', 'guard_name' => 'web'],
    ['name' => 'manager', 'guard_name' => 'web'],
    ['name' => 'staff', 'guard_name' => 'web'],
    ['name' => 'viewer', 'guard_name' => 'web'],
    ['name' => 'guest', 'guard_name' => 'web'],
];

foreach ($roles as $role) {
    Role::create($role);
}
```

#### PermissionSeeder.php
```php
$permissions = [
    // Commodities
    'commodities.view',
    'commodities.create',
    'commodities.edit',
    'commodities.delete',
    // ... (full list)
];

foreach ($permissions as $permission) {
    Permission::create([
        'name' => $permission,
        'guard_name' => 'web'
    ]);
}
```

#### RolePermissionSeeder.php
```php
$superAdmin = Role::findByName('super-admin');
$superAdmin->givePermissionTo(Permission::all());

$admin = Role::findByName('admin');
$admin->givePermissionTo([
    'commodities.view',
    'commodities.create',
    'commodities.edit',
    'commodities.delete',
    // ... (admin permissions)
]);

// ... repeat for other roles
```

### Step 5: Middleware

#### CheckRole.php
```php
if (!auth()->user()->hasRole($role)) {
    abort(403, 'Unauthorized: Insufficient role');
}
```

#### CheckPermission.php
```php
if (!auth()->user()->hasPermissionTo($permission)) {
    abort(403, 'Unauthorized: Missing permission');
}
```

### Step 6: Route Protection

#### web.php
```php
// Super Admin only
Route::middleware(['auth', 'role:super-admin'])->group(function() {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});

// Admin & Manager
Route::middleware(['auth', 'role:admin|manager'])->group(function() {
    Route::resource('commodities', CommodityController::class);
});

// Permission-based
Route::middleware(['auth', 'permission:commodities.create'])->group(function() {
    Route::post('/commodities', [CommodityController::class, 'store']);
});
```

---

## 6. Usage Examples

### Assign Role to User
```php
$user = User::find(1);
$user->assignRole('admin');

// Multiple roles
$user->assignRole(['admin', 'manager']);

// Remove role
$user->removeRole('manager');
```

### Check User Role
```php
// In controller
if ($user->hasRole('admin')) {
    // Do something
}

// In blade
@role('admin')
    <a href="/admin/panel">Admin Panel</a>
@endrole

// Check multiple
if ($user->hasAnyRole(['admin', 'manager'])) {
    // Allow access
}

if ($user->hasAllRoles(['admin', 'manager'])) {
    // Both required
}
```

### Direct Permissions
```php
// Give permission to user (bypass role)
$user->givePermissionTo('commodities.delete');

// Check permission
if ($user->hasPermissionTo('commodities.edit')) {
    // Allow edit
}

// In blade
@can('commodities.create')
    <button>Add Item</button>
@endcan
```

### Role Permissions
```php
// Give permissions to role
$role = Role::findByName('manager');
$role->givePermissionTo(['commodities.edit', 'reports.view']);

// Get all permissions of a role
$permissions = $role->permissions;

// Sync permissions (replace all)
$role->syncPermissions(['commodities.view', 'commodities.create']);
```

### Super Admin Bypass
```php
// In Gate definition
Gate::before(function ($user, $ability) {
    return $user->hasRole('super-admin') ? true : null;
});
```

---

## 7. Blade Directives

### Role-Based Display
```blade
@role('admin')
    <p>Admin-only content</p>
@endrole

@hasrole('manager')
    <p>Manager content</p>
@endhasrole

@hasanyrole('admin|manager|staff')
    <p>Staff and above</p>
@endhasanyrole

@unlessrole('guest')
    <p>Not for guests</p>
@endunlessrole
```

### Permission-Based Display
```blade
@can('commodities.create')
    <a href="{{ route('commodities.create') }}">Add Item</a>
@endcan

@cannot('commodities.delete')
    <p>You cannot delete items</p>
@endcannot

@canany(['commodities.edit', 'commodities.delete'])
    <button>Manage</button>
@endcanany
```

---

## 8. Advanced Scenarios

### Department-Scoped Permissions
```php
// In Policy
public function update(User $user, Commodity $commodity)
{
    if ($user->hasRole('super-admin')) {
        return true;
    }
    
    if ($user->hasRole('manager')) {
        return $user->department_id === $commodity->department_id;
    }
    
    return false;
}
```

### Dynamic Permissions
```php
// Create permission on-the-fly
Permission::findOrCreate('commodities.approve-' . $commodity->id);

$user->givePermissionTo('commodities.approve-' . $commodity->id);
```

### Temporary Permissions
```php
// Give permission for limited time
$user->givePermissionTo('reports.export');

// In scheduled job
if ($exportRequest->created_at->addHours(24) < now()) {
    $user->revokePermissionTo('reports.export');
}
```

---

## 9. Security Best Practices

### 1. Always Use Middleware
```php
// ❌ Bad
public function destroy($id)
{
    if (auth()->user()->hasRole('admin')) {
        Commodity::destroy($id);
    }
}

// ✅ Good
// In routes
Route::delete('/commodities/{id}', [CommodityController::class, 'destroy'])
    ->middleware('permission:commodities.delete');
```

### 2. Use Policies
```php
// ❌ Bad
if ($user->hasPermissionTo('commodities.edit')) {
    $commodity->update($data);
}

// ✅ Good
$this->authorize('update', $commodity);
```

### 3. Cache Permissions
```php
// Clear cache after role/permission changes
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

### 4. Validate Role Assignment
```php
// Don't allow self-promotion
if ($targetUser->id === auth()->id()) {
    throw new \Exception('Cannot modify your own roles');
}

// Don't allow creating super-admin
if ($role === 'super-admin' && !auth()->user()->hasRole('super-admin')) {
    throw new \Exception('Only super-admin can create super-admin');
}
```

### 5. Log Role Changes
```php
activity()
    ->causedBy(auth()->user())
    ->performedOn($targetUser)
    ->withProperties(['old_roles' => $oldRoles, 'new_roles' => $newRoles])
    ->log('Role changed');
```

---

## 10. Testing

### Unit Tests
```php
/** @test */
public function admin_can_create_commodities()
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    
    $this->actingAs($admin)
        ->post('/commodities', $data)
        ->assertStatus(201);
}

/** @test */
public function staff_cannot_delete_commodities()
{
    $staff = User::factory()->create();
    $staff->assignRole('staff');
    
    $commodity = Commodity::factory()->create();
    
    $this->actingAs($staff)
        ->delete("/commodities/{$commodity->id}")
        ->assertStatus(403);
}
```

### Feature Tests
```php
/** @test */
public function super_admin_can_access_all_routes()
{
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');
    
    $routes = [
        '/admin/settings',
        '/admin/users',
        '/admin/roles',
    ];
    
    foreach ($routes as $route) {
        $this->actingAs($superAdmin)
            ->get($route)
            ->assertStatus(200);
    }
}
```

---

## 11. Migration Plan

### Phase 1: Setup (Week 1)
- [ ] Install Spatie package
- [ ] Run migrations
- [ ] Create seeders
- [ ] Seed initial roles & permissions

### Phase 2: User Model (Week 1)
- [ ] Add HasRoles trait
- [ ] Test role assignment
- [ ] Update factories

### Phase 3: Middleware (Week 2)
- [ ] Create CheckRole middleware
- [ ] Create CheckPermission middleware
- [ ] Register in Kernel
- [ ] Test middleware

### Phase 4: Route Protection (Week 2-3)
- [ ] Audit all routes
- [ ] Apply middleware
- [ ] Test access control
- [ ] Update API routes

### Phase 5: UI Updates (Week 3-4)
- [ ] Add role/permission badges
- [ ] Hide/show elements based on permissions
- [ ] Update forms for role assignment
- [ ] Create role management interface

### Phase 6: Policies (Week 4)
- [ ] Create Commodity policy
- [ ] Create User policy
- [ ] Register policies
- [ ] Replace manual checks with authorize()

### Phase 7: Testing (Week 5)
- [ ] Write unit tests
- [ ] Write feature tests
- [ ] Load testing
- [ ] Security audit

### Phase 8: Documentation (Week 5)
- [ ] API documentation
- [ ] User manual
- [ ] Admin guide

---

## 12. Troubleshooting

### Issue: Permission Not Working
```php
// Clear cache
php artisan cache:forget spatie.permission.cache
php artisan permission:cache-reset

// Re-sync
php artisan permission:sync
```

### Issue: Role Check Fails After Assignment
```php
// Refresh user model
$user->refresh();

// Or reload permissions
$user->load('roles', 'permissions');
```

### Issue: Super Admin Not Bypassing
```php
// Check Gate::before in AuthServiceProvider
Gate::before(function ($user, $ability) {
    return $user->hasRole('super-admin') ? true : null;
});
```

---

## 13. Performance Optimization

### Cache Strategy
```php
// Cache user permissions for 1 hour
Cache::remember("user.{$userId}.permissions", 3600, function() use ($user) {
    return $user->getAllPermissions()->pluck('name');
});
```

### Eager Loading
```php
// Load roles & permissions upfront
$users = User::with('roles.permissions')->get();
```

### Database Indexing
```sql
CREATE INDEX idx_model_has_roles_model ON model_has_roles(model_id, model_type);
CREATE INDEX idx_model_has_permissions_model ON model_has_permissions(model_id, model_type);
```

---

## 14. Audit & Logging

### Track Permission Changes
```php
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use LogsActivity;
    
    protected static $logAttributes = ['name', 'email'];
    protected static $logOnlyDirty = true;
}
```

### Custom Audit Log
```php
activity()
    ->causedBy(auth()->user())
    ->performedOn($commodity)
    ->withProperties(['action' => 'delete', 'reason' => $reason])
    ->log('Commodity deleted');
```

---

## 15. Checklist

### Pre-Implementation
- [ ] Database design approved
- [ ] Role matrix defined
- [ ] Permission list finalized
- [ ] Stakeholders reviewed

### Implementation
- [ ] Spatie installed
- [ ] Migrations run
- [ ] Seeders executed
- [ ] Middleware created
- [ ] Routes protected
- [ ] Blade directives implemented
- [ ] Policies created

### Post-Implementation
- [ ] All tests passing
- [ ] Documentation complete
- [ ] Training materials ready
- [ ] Monitoring in place
- [ ] Rollback plan prepared

---

## 16. References

- **Spatie Docs**: https://spatie.be/docs/laravel-permission
- **Laravel Auth**: https://laravel.com/docs/authorization
- **Best Practices**: https://laravel.com/docs/security

---

**Document Version**: 1.0  
**Last Updated**: {{ date }}  
**Author**: Development Team  
**Status**: Ready for Implementation
