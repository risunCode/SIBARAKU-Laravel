# Implementasi Sistem Notifikasi
## Inventaris Barang - Complete Guide

---

## 1. Overview Sistem

### Konsep Dasar
- **Event-Driven**: Notifikasi triggered oleh events di sistem
- **Multi-Channel**: Database, Email, SMS, Real-time (WebSocket)
- **Role-Based Targeting**: Notifikasi ke role/permission tertentu
- **Queue-Based**: Async processing untuk performa optimal
- **Customizable**: User bisa set preference notifikasi

### Notification Flow
```
Event Trigger → Notification Class → Channels → Queue → Send → Store/Log
                                                          ↓
                                              User Receives Notification
```

### Best Practices
1. **Always Queue** - Jangan kirim notifikasi synchronously
2. **Batched Notifications** - Group similar notifications
3. **User Preferences** - Respect user notification settings
4. **Rate Limiting** - Prevent notification spam
5. **Soft Deletes** - Keep notification history

---

## 2. Database Schema

### `notifications` Table (Laravel Default)
```sql
id                  CHAR(36) PRIMARY KEY        -- UUID
type                VARCHAR(255)                -- Notification class name
notifiable_type     VARCHAR(255)                -- App\Models\User
notifiable_id       BIGINT UNSIGNED             -- User ID
data                TEXT                        -- JSON data
read_at             TIMESTAMP NULL              -- When marked as read
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX(notifiable_type, notifiable_id)
INDEX(read_at)
INDEX(created_at)
```

### `notification_preferences` Table (Custom)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
user_id             BIGINT UNSIGNED             -- FK to users
notification_type   VARCHAR(255)                -- Type of notification
channel_email       BOOLEAN DEFAULT true        -- Enable email
channel_database    BOOLEAN DEFAULT true        -- Enable in-app
channel_sms         BOOLEAN DEFAULT false       -- Enable SMS
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE(user_id, notification_type)
FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
```

### `notification_logs` Table (Custom - for debugging)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
notification_id     CHAR(36)                    -- FK to notifications
user_id             BIGINT UNSIGNED             -- FK to users
channel             VARCHAR(50)                 -- email, database, sms
status              ENUM('sent', 'failed', 'pending')
error_message       TEXT NULL                   -- If failed
sent_at             TIMESTAMP NULL
created_at          TIMESTAMP

INDEX(notification_id)
INDEX(user_id)
INDEX(status)
```

### `notification_templates` Table (Optional)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
type                VARCHAR(255) UNIQUE         -- notification type
subject             VARCHAR(255)                -- Email subject
body_email          TEXT                        -- Email body template
body_database       TEXT                        -- In-app body template
body_sms            TEXT                        -- SMS body template
variables           JSON                        -- Available variables
is_active           BOOLEAN DEFAULT true
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

---

## 3. Notification Types & Events

### Inventory Management

#### 1. Item Created
```yaml
event: ItemCreated
notification: ItemCreatedNotification
trigger: Setelah commodity baru ditambahkan
recipients: 
  - Admin (all)
  - Manager (department)
channels: [database, email]
priority: normal
data:
  - item_id
  - item_name
  - item_code
  - created_by
  - department
```

#### 2. Low Stock Alert
```yaml
event: LowStockDetected
notification: LowStockNotification
trigger: Stock < minimum threshold
recipients:
  - Manager (responsible)
  - Admin (all)
channels: [database, email, sms]
priority: high
frequency: Once per day (batched)
data:
  - item_id
  - item_name
  - current_stock
  - minimum_stock
  - location
```

#### 3. Item Updated
```yaml
event: ItemUpdated
notification: ItemUpdatedNotification
trigger: Commodity data changed
recipients:
  - Manager (department)
  - User (if assigned)
channels: [database]
priority: low
data:
  - item_id
  - item_name
  - changes (JSON)
  - updated_by
```

#### 4. Item Deleted
```yaml
event: ItemDeleted
notification: ItemDeletedNotification
trigger: Commodity soft/hard deleted
recipients:
  - Admin (all)
  - Manager (department)
channels: [database, email]
priority: high
data:
  - item_id
  - item_name
  - deleted_by
  - deletion_type (soft/hard)
  - reason
```

### Transfer Management

#### 5. Transfer Request Created
```yaml
event: TransferRequested
notification: TransferRequestNotification
trigger: User requests item transfer
recipients:
  - Manager (from location)
  - Manager (to location)
  - Admin (all)
channels: [database, email]
priority: normal
data:
  - transfer_id
  - item_name
  - from_location
  - to_location
  - requested_by
  - reason
```

#### 6. Transfer Approved
```yaml
event: TransferApproved
notification: TransferApprovedNotification
trigger: Manager approves transfer
recipients:
  - User (requester)
  - Staff (destination location)
channels: [database, email]
priority: normal
data:
  - transfer_id
  - item_name
  - approved_by
  - expected_date
```

#### 7. Transfer Rejected
```yaml
event: TransferRejected
notification: TransferRejectedNotification
trigger: Manager rejects transfer
recipients:
  - User (requester)
channels: [database, email]
priority: normal
data:
  - transfer_id
  - item_name
  - rejected_by
  - rejection_reason
```

#### 8. Transfer Completed
```yaml
event: TransferCompleted
notification: TransferCompletedNotification
trigger: Item successfully moved
recipients:
  - User (requester)
  - Manager (both locations)
channels: [database]
priority: low
data:
  - transfer_id
  - item_name
  - completed_by
  - completion_date
```

### Maintenance Management

#### 9. Maintenance Scheduled
```yaml
event: MaintenanceScheduled
notification: MaintenanceScheduledNotification
trigger: Maintenance dijadwalkan
recipients:
  - Staff (assigned)
  - Manager (department)
channels: [database, email]
priority: normal
data:
  - maintenance_id
  - item_name
  - scheduled_date
  - assigned_to
  - description
```

#### 10. Maintenance Due
```yaml
event: MaintenanceDue
notification: MaintenanceDueNotification
trigger: 3 hari sebelum maintenance
recipients:
  - Staff (assigned)
  - Manager (department)
channels: [database, email, sms]
priority: high
frequency: Daily reminder
data:
  - maintenance_id
  - item_name
  - due_date
  - assigned_to
```

#### 11. Maintenance Completed
```yaml
event: MaintenanceCompleted
notification: MaintenanceCompletedNotification
trigger: Maintenance selesai dilakukan
recipients:
  - Manager (department)
  - Admin (if cost > threshold)
channels: [database, email]
priority: normal
data:
  - maintenance_id
  - item_name
  - completed_by
  - cost
  - next_schedule
```

#### 12. Maintenance Overdue
```yaml
event: MaintenanceOverdue
notification: MaintenanceOverdueNotification
trigger: Maintenance melewati due date
recipients:
  - Staff (assigned)
  - Manager (department)
  - Admin (escalation after 7 days)
channels: [database, email, sms]
priority: urgent
frequency: Daily until completed
data:
  - maintenance_id
  - item_name
  - days_overdue
  - assigned_to
```

### Disposal Management

#### 13. Disposal Request
```yaml
event: DisposalRequested
notification: DisposalRequestNotification
trigger: User requests item disposal/write-off
recipients:
  - Manager (department)
  - Admin (all)
channels: [database, email]
priority: high
data:
  - disposal_id
  - item_name
  - reason (sold/damaged/obsolete)
  - requested_by
  - estimated_value
```

#### 14. Disposal Approved
```yaml
event: DisposalApproved
notification: DisposalApprovedNotification
trigger: Disposal request disetujui
recipients:
  - User (requester)
  - Admin (finance)
channels: [database, email]
priority: normal
data:
  - disposal_id
  - item_name
  - approved_by
  - disposal_value
  - disposal_date
```

#### 15. Disposal Rejected
```yaml
event: DisposalRejected
notification: DisposalRejectedNotification
trigger: Disposal request ditolak
recipients:
  - User (requester)
channels: [database, email]
priority: normal
data:
  - disposal_id
  - item_name
  - rejected_by
  - rejection_reason
```

### User Management

#### 16. User Role Changed
```yaml
event: UserRoleChanged
notification: RoleChangedNotification
trigger: Admin mengubah role user
recipients:
  - User (affected)
  - Admin (who made change)
channels: [database, email]
priority: high
data:
  - user_id
  - user_name
  - old_roles
  - new_roles
  - changed_by
```

#### 17. User Account Created
```yaml
event: UserCreated
notification: UserCreatedNotification
trigger: Admin membuat user baru
recipients:
  - User (new)
channels: [email]
priority: high
data:
  - user_name
  - email
  - temporary_password
  - login_url
```

#### 18. Password Reset Request
```yaml
event: PasswordResetRequested
notification: PasswordResetNotification
trigger: User lupa password
recipients:
  - User (requester)
channels: [email]
priority: urgent
data:
  - user_name
  - reset_token
  - reset_url
  - expiry_time
```

### Reports & System

#### 19. Report Generated
```yaml
event: ReportGenerated
notification: ReportReadyNotification
trigger: Report selesai di-generate
recipients:
  - User (requester)
channels: [database, email]
priority: normal
data:
  - report_id
  - report_type
  - file_url
  - generated_by
  - expiry_date
```

#### 20. Batch Import Completed
```yaml
event: ImportCompleted
notification: ImportCompletedNotification
trigger: Bulk import selesai
recipients:
  - User (who imported)
  - Admin (if errors exist)
channels: [database, email]
priority: normal
data:
  - import_id
  - total_records
  - success_count
  - error_count
  - error_file_url
```

#### 21. System Backup Completed
```yaml
event: BackupCompleted
notification: BackupCompletedNotification
trigger: Scheduled backup selesai
recipients:
  - Admin (super-admin only)
channels: [email]
priority: low
data:
  - backup_id
  - file_size
  - backup_path
  - duration
```

#### 22. System Error Alert
```yaml
event: SystemError
notification: SystemErrorNotification
trigger: Critical error di sistem
recipients:
  - Admin (super-admin)
  - Developer (if configured)
channels: [email, sms]
priority: critical
data:
  - error_message
  - stack_trace
  - affected_module
  - timestamp
  - user_id (if applicable)
```

### Audit & Compliance

#### 23. Unauthorized Access Attempt
```yaml
event: UnauthorizedAccessAttempt
notification: SecurityAlertNotification
trigger: User mencoba akses forbidden resource
recipients:
  - Admin (super-admin)
  - Security team
channels: [database, email]
priority: urgent
data:
  - user_id
  - ip_address
  - attempted_resource
  - timestamp
```

#### 24. Bulk Delete Warning
```yaml
event: BulkDeleteAttempted
notification: BulkDeleteWarningNotification
trigger: User mencoba delete > 10 items
recipients:
  - Admin (all)
  - Manager (department)
channels: [database, email]
priority: high
data:
  - user_id
  - item_count
  - affected_items
  - timestamp
```

---

## 4. Implementation

### Step 1: Setup Database Notifications

#### Migration
```php
// Already exists in Laravel
php artisan notifications:table
php artisan migrate

// Custom tables
php artisan make:migration create_notification_preferences_table
php artisan make:migration create_notification_logs_table
```

#### User Model
```php
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    // Override default notification routing
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
    
    public function routeNotificationForSms($notification)
    {
        return $this->phone;
    }
    
    // Relationships
    public function notificationPreferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }
}
```

### Step 2: Create Notification Classes

#### Low Stock Notification
```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LowStockNotification extends Notification
{
    use Queueable;
    
    protected $commodity;
    protected $currentStock;
    protected $minimumStock;
    
    public function __construct($commodity, $currentStock, $minimumStock)
    {
        $this->commodity = $commodity;
        $this->currentStock = $currentStock;
        $this->minimumStock = $minimumStock;
    }
    
    // Define channels
    public function via($notifiable)
    {
        // Check user preferences
        $channels = [];
        
        if ($notifiable->prefers('low_stock', 'database')) {
            $channels[] = 'database';
        }
        
        if ($notifiable->prefers('low_stock', 'email')) {
            $channels[] = 'mail';
        }
        
        if ($this->currentStock === 0 && $notifiable->prefers('low_stock', 'sms')) {
            $channels[] = 'sms';
        }
        
        return $channels;
    }
    
    // Database notification
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'low_stock',
            'commodity_id' => $this->commodity->id,
            'commodity_name' => $this->commodity->name,
            'current_stock' => $this->currentStock,
            'minimum_stock' => $this->minimumStock,
            'location' => $this->commodity->location->name,
            'priority' => $this->currentStock === 0 ? 'critical' : 'warning',
            'action_url' => route('commodities.show', $this->commodity->id),
        ];
    }
    
    // Email notification
    public function toMail($notifiable)
    {
        $priority = $this->currentStock === 0 ? 'Critical' : 'Warning';
        
        return (new MailMessage)
            ->subject("[{$priority}] Low Stock Alert: {$this->commodity->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Stock for **{$this->commodity->name}** is running low.")
            ->line("Current Stock: **{$this->currentStock}**")
            ->line("Minimum Required: **{$this->minimumStock}**")
            ->line("Location: **{$this->commodity->location->name}**")
            ->action('View Item', route('commodities.show', $this->commodity->id))
            ->line('Please take necessary action to replenish stock.');
    }
    
    // SMS notification (via Nexmo/Twilio)
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content("ALERT: {$this->commodity->name} stock is {$this->currentStock}. Min: {$this->minimumStock}. Check system now.");
    }
}
```

#### Transfer Request Notification
```php
class TransferRequestNotification extends Notification
{
    use Queueable;
    
    protected $transfer;
    
    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }
    
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }
    
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'transfer_request',
            'transfer_id' => $this->transfer->id,
            'commodity_name' => $this->transfer->commodity->name,
            'from_location' => $this->transfer->fromLocation->name,
            'to_location' => $this->transfer->toLocation->name,
            'requested_by' => $this->transfer->requester->name,
            'reason' => $this->transfer->reason,
            'priority' => 'normal',
            'action_url' => route('transfers.show', $this->transfer->id),
            'actions' => [
                [
                    'label' => 'Approve',
                    'url' => route('transfers.approve', $this->transfer->id),
                    'type' => 'success'
                ],
                [
                    'label' => 'Reject',
                    'url' => route('transfers.reject', $this->transfer->id),
                    'type' => 'danger'
                ]
            ]
        ];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Transfer Request: {$this->transfer->commodity->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("{$this->transfer->requester->name} has requested a transfer:")
            ->line("**Item:** {$this->transfer->commodity->name}")
            ->line("**From:** {$this->transfer->fromLocation->name}")
            ->line("**To:** {$this->transfer->toLocation->name}")
            ->line("**Reason:** {$this->transfer->reason}")
            ->action('Review Request', route('transfers.show', $this->transfer->id))
            ->line('Please review and take action.');
    }
}
```

### Step 3: Create Events

#### Event Class
```php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockDetected
{
    use Dispatchable, SerializesModels;
    
    public $commodity;
    public $currentStock;
    public $minimumStock;
    
    public function __construct($commodity, $currentStock, $minimumStock)
    {
        $this->commodity = $commodity;
        $this->currentStock = $currentStock;
        $this->minimumStock = $minimumStock;
    }
}
```

#### Event Listener
```php
namespace App\Listeners;

use App\Events\LowStockDetected;
use App\Notifications\LowStockNotification;
use App\Models\User;

class SendLowStockNotification
{
    public function handle(LowStockDetected $event)
    {
        // Get managers in the same department
        $managers = User::role('manager')
            ->where('department_id', $event->commodity->department_id)
            ->get();
        
        // Get all admins
        $admins = User::role('admin')->get();
        
        // Merge recipients
        $recipients = $managers->merge($admins)->unique('id');
        
        // Send notification
        Notification::send(
            $recipients,
            new LowStockNotification(
                $event->commodity,
                $event->currentStock,
                $event->minimumStock
            )
        );
    }
}
```

#### Register Event & Listener
```php
// EventServiceProvider.php
protected $listen = [
    LowStockDetected::class => [
        SendLowStockNotification::class,
    ],
    TransferRequested::class => [
        SendTransferRequestNotification::class,
    ],
    MaintenanceDue::class => [
        SendMaintenanceDueNotification::class,
    ],
    // ... other events
];
```

### Step 4: Trigger Notifications

#### From Controller
```php
// CommodityController.php
public function update(Request $request, Commodity $commodity)
{
    $validated = $request->validate([...]);
    
    $commodity->update($validated);
    
    // Check if stock is low
    if ($commodity->quantity < $commodity->minimum_stock) {
        event(new LowStockDetected(
            $commodity,
            $commodity->quantity,
            $commodity->minimum_stock
        ));
    }
    
    return redirect()->route('commodities.index')
        ->with('success', 'Item updated successfully');
}
```

#### From Observer
```php
// CommodityObserver.php
class CommodityObserver
{
    public function updated(Commodity $commodity)
    {
        if ($commodity->wasChanged('quantity')) {
            if ($commodity->quantity < $commodity->minimum_stock) {
                event(new LowStockDetected(
                    $commodity,
                    $commodity->quantity,
                    $commodity->minimum_stock
                ));
            }
        }
    }
}
```

#### From Scheduled Job
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Check for maintenance due daily at 8 AM
    $schedule->call(function () {
        $maintenances = Maintenance::whereBetween('scheduled_date', [
            now(),
            now()->addDays(3)
        ])->get();
        
        foreach ($maintenances as $maintenance) {
            event(new MaintenanceDue($maintenance));
        }
    })->dailyAt('08:00');
    
    // Check overdue maintenance daily at 9 AM
    $schedule->call(function () {
        $overdueMaintenances = Maintenance::where('scheduled_date', '<', now())
            ->whereNull('completed_at')
            ->get();
        
        foreach ($overdueMaintenances as $maintenance) {
            event(new MaintenanceOverdue($maintenance));
        }
    })->dailyAt('09:00');
}
```

### Step 5: User Preferences

#### Model
```php
namespace App\Models;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'notification_type',
        'channel_email',
        'channel_database',
        'channel_sms'
    ];
    
    protected $casts = [
        'channel_email' => 'boolean',
        'channel_database' => 'boolean',
        'channel_sms' => 'boolean',
    ];
}
```

#### Helper Method in User Model
```php
// User.php
public function prefers($notificationType, $channel)
{
    $preference = $this->notificationPreferences()
        ->where('notification_type', $notificationType)
        ->first();
    
    if (!$preference) {
        // Default preferences
        return $channel === 'database' ? true : false;
    }
    
    return $preference->{"channel_{$channel}"};
}

public function setNotificationPreference($type, $channels)
{
    return $this->notificationPreferences()->updateOrCreate(
        ['notification_type' => $type],
        $channels
    );
}
```

#### Controller
```php
// NotificationPreferenceController.php
public function update(Request $request)
{
    $validated = $request->validate([
        'preferences' => 'required|array',
        'preferences.*.type' => 'required|string',
        'preferences.*.email' => 'boolean',
        'preferences.*.database' => 'boolean',
        'preferences.*.sms' => 'boolean',
    ]);
    
    foreach ($validated['preferences'] as $pref) {
        auth()->user()->setNotificationPreference($pref['type'], [
            'channel_email' => $pref['email'] ?? false,
            'channel_database' => $pref['database'] ?? true,
            'channel_sms' => $pref['sms'] ?? false,
        ]);
    }
    
    return back()->with('success', 'Preferences updated');
}
```

---

## 5. Real-Time Notifications

### Using Laravel Echo + Pusher

#### Install Dependencies
```bash
composer require pusher/pusher-php-server
npm install --save-dev laravel-echo pusher-js
```

#### Configuration
```php
// config/broadcasting.php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true,
    ],
],
```

#### Broadcast Notification
```php
// Notification class
class TransferRequestNotification extends Notification implements ShouldBroadcast
{
    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }
    
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'transfer_request',
            'transfer_id' => $this->transfer->id,
            'commodity_name' => $this->transfer->commodity->name,
            'message' => "New transfer request for {$this->transfer->commodity->name}",
            'action_url' => route('transfers.show', $this->transfer->id),
        ]);
    }
}
```

#### Frontend (Vue.js example)
```javascript
// resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen to private channel
window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // Show notification toast
        showToast(notification);
        
        // Update notification bell counter
        updateNotificationCount();
        
        // Play sound
        playNotificationSound();
    });
```

---

## 6. UI Components

### Notification Bell (Blade)
```blade
<!-- resources/views/components/notification-bell.blade.php -->
<div class="relative" x-data="{ open: false }">
    <!-- Bell Icon -->
    <button @click="open = !open" class="relative">
        <svg class="w-6 h-6"><!-- Bell icon --></svg>
        
        @if($unreadCount > 0)
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>
    
    <!-- Dropdown -->
    <div x-show="open" @click.away="open = false" 
         class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg">
        
        <div class="p-4 border-b">
            <h3 class="font-semibold">Notifications</h3>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
            <a href="{{ $notification->data['action_url'] ?? '#' }}" 
               class="block p-4 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50' }}">
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @include('notifications.icons.' . $notification->data['type'])
                    </div>
                    
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium">
                            {{ $notification->data['message'] ?? 'New notification' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    @if(!$notification->read_at)
                    <div class="ml-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons (if any) -->
                @if(isset($notification->data['actions']))
                <div class="mt-3 flex gap-2">
                    @foreach($notification->data['actions'] as $action)
                    <button class="btn btn-{{ $action['type'] }} btn-sm">
                        {{ $action['label'] }}
                    </button>
                    @endforeach
                </div>
                @endif
            </a>
            @empty
            <div class="p-8 text-center text-gray-500">
                No notifications
            </div>
            @endforelse
        </div>
        
        <div class="p-3 border-t text-center">
            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:underline">
                View All Notifications
            </a>
        </div>
    </div>
</div>
```

### Notification Controller
```php
class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);
        
        $notification->markAsRead();
        
        return back();
    }
    
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'All notifications marked as read');
    }
    
    public function destroy($id)
    {
        auth()->user()
            ->notifications()
            ->findOrFail($id)
            ->delete();
        
        return back()->with('success', 'Notification deleted');
    }
}
```

---

## 7. Batching & Rate Limiting

### Batch Notifications
```php
// Instead of sending 50 separate "low stock" emails
// Batch them into one digest

class DailyLowStockDigest extends Notification
{
    protected $lowStockItems;
    
    public function __construct($lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Daily Low Stock Report - " . now()->format('Y-m-d'))
            ->markdown('emails.low-stock-digest', [
                'items' => $this->lowStockItems,
                'user' => $notifiable
            ]);
    }
}

// Scheduled job
$schedule->call(function () {
    $lowStockItems = Commodity::where('quantity', '<', DB::raw('minimum_stock'))
        ->get();
    
    if ($lowStockItems->isNotEmpty()) {
        $managers = User::role('manager')->get();
        
        Notification::send($managers, new DailyLowStockDigest($lowStockItems));
    }
})->dailyAt('08:00');
```

### Rate Limiting
```php
// Prevent spam notifications for same event
use Illuminate\Support\Facades\Cache;

class SendLowStockNotification
{
    public function handle(LowStockDetected $event)
    {
        $cacheKey = "low_stock_notified_{$event->commodity->id}";
        
        // Only send once per day
        if (Cache::has($cacheKey)) {
            return;
        }
        
        // Send notification
        Notification::send($recipients, new LowStockNotification(...));
        
        // Cache for 24 hours
        Cache::put($cacheKey, true, now()->addDay());
    }
}
```

---

## 8. Advanced Features

### Notification Channels

#### Custom SMS Channel (Twilio)
```php
namespace App\Channels;

use Twilio\Rest\Client;

class TwilioChannel
{
    public function send($notifiable, $notification)
    {
        $message = $notification->toTwilio($notifiable);
        
        $to = $notifiable->routeNotificationForTwilio();
        
        $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        
        return $twilio->messages->create($to, [
            'from' => config('services.twilio.from'),
            'body' => $message->content
        ]);
    }
}
```

#### WhatsApp Channel
```php
namespace App\Channels;

class WhatsAppChannel
{
    public function send($notifiable, $notification)
    {
        $message = $notification->toWhatsApp($notifiable);
        $to = $notifiable->routeNotificationForWhatsApp();
        
        // Use WhatsApp Business API
        Http::withToken(config('services.whatsapp.token'))
            ->post(config('services.whatsapp.url'), [
                'to' => $to,
                'type' => 'text',
                'text' => ['body' => $message->content]
            ]);
    }
}
```

### Priority Queue
```php
// High priority notifications go to 'high' queue
class LowStockNotification extends Notification implements ShouldQueue
{
    public $queue = 'high';
    public $delay = 0;
    
    public function __construct($commodity)
    {
        if ($commodity->quantity === 0) {
            $this->queue = 'urgent';
        }
    }
}

// Worker prioritization
php artisan queue:work --queue=urgent,high,default,low
```

### Notification Actions (Interactive)
```php
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Transfer Request Approval')
        ->line('A transfer request needs your approval')
        ->action('Approve', route('transfers.approve', $this->transfer))
        ->action('Reject', route('transfers.reject', $this->transfer));
}
```

---

## 9. Testing

### Unit Tests
```php
use Illuminate\Support\Facades\Notification;

/** @test */
public function low_stock_notification_is_sent_when_stock_drops()
{
    Notification::fake();
    
    $commodity = Commodity::factory()->create([
        'quantity' => 5,
        'minimum_stock' => 10
    ]);
    
    event(new LowStockDetected($commodity, 5, 10));
    
    Notification::assertSentTo(
        User::role('manager')->get(),
        LowStockNotification::class
    );
}

/** @test */
public function notification_respects_user_preferences()
{
    $user = User::factory()->create();
    $user->setNotificationPreference('low_stock', [
        'channel_email' => false,
        'channel_database' => true,
        'channel_sms' => false
    ]);
    
    $notification = new LowStockNotification($commodity, 5, 10);
    
    $channels = $notification->via($user);
    
    $this->assertContains('database', $channels);
    $this->assertNotContains('mail', $channels);
}
```

### Feature Tests
```php
/** @test */
public function user_can_mark_notification_as_read()
{
    $user = User::factory()->create();
    $notification = $user->notify(new TestNotification());
    
    $this->actingAs($user)
        ->post("/notifications/{$notification->id}/read")
        ->assertRedirect();
    
    $this->assertNotNull($notification->fresh()->read_at);
}
```

---

## 10. Performance Optimization

### Database Indexing
```sql
-- notifications table
CREATE INDEX idx_notifiable ON notifications(notifiable_type, notifiable_id);
CREATE INDEX idx_read_at ON notifications(read_at);
CREATE INDEX idx_created_at ON notifications(created_at);

-- Composite index for unread notifications
CREATE INDEX idx_unread_user ON notifications(notifiable_id, read_at) WHERE read_at IS NULL;
```

### Eager Loading
```php
// Load notifications with relationships
$notifications = auth()->user()
    ->notifications()
    ->with(['notifiable']) // If needed
    ->latest()
    ->paginate(20);
```

### Notification Pruning
```php
// Delete old read notifications
$schedule->command('model:prune', [
    '--model' => 'Illuminate\Notifications\DatabaseNotification'
])->daily();

// In DatabaseNotification model (extend it)
public function prunable()
{
    return static::where('read_at', '<', now()->subDays(30));
}
```

### Caching Unread Count
```php
// User model
public function getUnreadNotificationsCountAttribute()
{
    return Cache::remember("user.{$this->id}.unread_count", 300, function () {
        return $this->unreadNotifications()->count();
    });
}

// Clear cache when notification is read
public function markNotificationAsRead($notificationId)
{
    $this->notifications()->find($notificationId)->markAsRead();
    Cache::forget("user.{$this->id}.unread_count");
}
```

---

## 11. Security Considerations

### 1. Prevent Notification Leakage
```php
// Ensure user can only access their own notifications
Route::get('/notifications/{id}', function($id) {
    $notification = auth()->user()
        ->notifications()
        ->findOrFail($id); // 404 if not belongs to user
    
    return view('notifications.show', compact('notification'));
});
```

### 2. Sanitize Notification Data
```php
public function toDatabase($notifiable)
{
    return [
        'message' => strip_tags($this->message),
        'url' => filter_var($this->url, FILTER_SANITIZE_URL),
    ];
}
```

### 3. Rate Limit Notification Actions
```php
Route::post('/notifications/{id}/action', [NotificationController::class, 'action'])
    ->middleware('throttle:10,1'); // 10 requests per minute
```

---

## 12. Monitoring & Logging

### Failed Notifications Log
```php
// Create custom log channel
'channels' => [
    'notifications' => [
        'driver' => 'daily',
        'path' => storage_path('logs/notifications.log'),
        'level' => 'info',
        'days' => 14,
    ],
],

// In notification class
public function failed(Exception $exception)
{
    Log::channel('notifications')->error('Notification failed', [
        'notification' => get_class($this),
        'exception' => $exception->getMessage(),
        'data' => $this->toArray()
    ]);
}
```

### Metrics & Analytics
```php
// Track notification metrics
class NotificationMetrics
{
    public static function track($notification, $channel, $status)
    {
        DB::table('notification_metrics')->insert([
            'notification_type' => get_class($notification),
            'channel' => $channel,
            'status' => $status,
            'sent_at' => now()
        ]);
    }
}
```

---

## 13. Migration Checklist

### Phase 1: Setup (Week 1)
- [ ] Create notifications table migration
- [ ] Create notification_preferences table
- [ ] Create notification_logs table
- [ ] Setup queue workers
- [ ] Configure mail driver

### Phase 2: Core Notifications (Week 2)
- [ ] Create notification classes (top 10)
- [ ] Create events & listeners
- [ ] Setup notification preferences
- [ ] Test email delivery

### Phase 3: Real-Time (Week 3)
- [ ] Setup Pusher/WebSocket
- [ ] Implement Laravel Echo
- [ ] Create frontend notification bell
- [ ] Test real-time delivery

### Phase 4: Advanced (Week 4)
- [ ] Implement SMS channel
- [ ] Create batched notifications
- [ ] Setup rate limiting
- [ ] Add notification templates

### Phase 5: Polish (Week 5)
- [ ] Notification preferences UI
- [ ] Notification history page
- [ ] Performance optimization
- [ ] Testing & debugging

---

## 14. Troubleshooting

### Notifications Not Sending
```bash
# Check queue workers
php artisan queue:work --verbose

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear notification cache
php artisan cache:clear
php artisan config:clear
```

### Email Not Delivering
```php
// Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });

// Check mail logs
tail -f storage/logs/laravel.log
```

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Status**: Ready for Implementation
