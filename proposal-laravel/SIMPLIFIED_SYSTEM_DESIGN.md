# Simplified System Design - Inventaris Barang
## Lightweight, Mobile-First, Simple Approach

---

## 1. Design Principles

### Core Philosophy
- **Simple is Better**: Hanya fitur essential, no bloat
- **Mobile-First**: Responsive design, touch-friendly
- **Fast & Lightweight**: Minimal queries, optimized assets
- **No Overkill**: Database schema sesimpel mungkin
- **Pragmatic Security**: Security questions, no OTP complexity

### Technical Goals
- **Page Load**: < 2 seconds on 3G
- **Database**: < 10 tables
- **CSS Framework**: Tailwind CSS (purged)
- **JS**: Minimal, Alpine.js only
- **No Real-Time**: Polling-based updates only

---

## 2. Simplified Database Schema

### Core Tables (8 Tables Only)

#### 1. `users`
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
name                VARCHAR(255)
email               VARCHAR(255) UNIQUE
password            VARCHAR(255)
phone               VARCHAR(20) NULL
role                ENUM('admin', 'manager', 'staff', 'viewer') DEFAULT 'staff'
department_id       BIGINT UNSIGNED NULL
is_active           BOOLEAN DEFAULT true
security_question_1 VARCHAR(255)        -- Pertanyaan keamanan 1
security_answer_1   VARCHAR(255)        -- Jawaban (hashed)
security_question_2 VARCHAR(255)        -- Pertanyaan keamanan 2
security_answer_2   VARCHAR(255)        -- Jawaban (hashed)
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX(email)
INDEX(role)
INDEX(department_id)
```

#### 2. `commodities` (Main Inventory)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
item_code           VARCHAR(50) UNIQUE
name                VARCHAR(255)
category            VARCHAR(100)        -- Simple string, no foreign key
brand               VARCHAR(100)
location            VARCHAR(100)        -- Simple string, no foreign key
acquisition_type    VARCHAR(50)         -- purchase, donation, grant
quantity            INTEGER DEFAULT 1
condition           TINYINT DEFAULT 5   -- 1-5 scale
purchase_year       YEAR
purchase_price      DECIMAL(15,2)
notes               TEXT NULL
responsible_person  VARCHAR(255) NULL
created_by          BIGINT UNSIGNED
updated_by          BIGINT UNSIGNED NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
deleted_at          TIMESTAMP NULL      -- Soft delete

INDEX(item_code)
INDEX(category)
INDEX(location)
INDEX(created_by)
INDEX(deleted_at)
FULLTEXT(name, brand, notes)
```

#### 3. `commodity_images`
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
commodity_id        BIGINT UNSIGNED
image_path          VARCHAR(255)
is_primary          BOOLEAN DEFAULT false
created_at          TIMESTAMP

FOREIGN KEY(commodity_id) REFERENCES commodities(id) ON DELETE CASCADE
INDEX(commodity_id)
```

#### 4. `transfers`
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
commodity_id        BIGINT UNSIGNED
from_location       VARCHAR(100)
to_location         VARCHAR(100)
requested_by        BIGINT UNSIGNED
approved_by         BIGINT UNSIGNED NULL
status              ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending'
reason              TEXT
rejection_reason    TEXT NULL
transfer_date       DATE NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP

FOREIGN KEY(commodity_id) REFERENCES commodities(id) ON DELETE CASCADE
INDEX(status)
INDEX(requested_by)
```

#### 5. `maintenance_logs`
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
commodity_id        BIGINT UNSIGNED
maintenance_date    DATE
description         TEXT
cost                DECIMAL(15,2) DEFAULT 0
performed_by        VARCHAR(255) NULL
next_maintenance    DATE NULL
created_at          TIMESTAMP

FOREIGN KEY(commodity_id) REFERENCES commodities(id) ON DELETE CASCADE
INDEX(commodity_id)
INDEX(next_maintenance)
```

#### 6. `disposals`
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
commodity_id        BIGINT UNSIGNED
disposal_date       DATE
reason              ENUM('sold', 'damaged', 'obsolete', 'donated', 'lost')
disposal_value      DECIMAL(15,2) NULL
notes               TEXT NULL
requested_by        BIGINT UNSIGNED
approved_by         BIGINT UNSIGNED NULL
status              ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'
created_at          TIMESTAMP
updated_at          TIMESTAMP

FOREIGN KEY(commodity_id) REFERENCES commodities(id) ON DELETE CASCADE
INDEX(status)
```

#### 7. `activity_logs` (Simple Audit)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
user_id             BIGINT UNSIGNED NULL
action              VARCHAR(50)         -- created, updated, deleted, etc
model_type          VARCHAR(50)         -- commodity, transfer, etc
model_id            BIGINT UNSIGNED NULL
description         VARCHAR(255)
ip_address          VARCHAR(45) NULL
created_at          TIMESTAMP

INDEX(user_id)
INDEX(model_type, model_id)
INDEX(created_at)
```

#### 8. `notifications` (Simplified)
```sql
id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
user_id             BIGINT UNSIGNED
type                VARCHAR(50)         -- low_stock, transfer_request, etc
title               VARCHAR(255)
message             TEXT
link                VARCHAR(255) NULL
is_read             BOOLEAN DEFAULT false
created_at          TIMESTAMP

FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
INDEX(user_id, is_read)
INDEX(created_at)
```

**Total: 8 Tables** (vs 20+ tables di dokumentasi sebelumnya)

---

## 3. Security Questions System

### Predefined Questions List
```php
// config/security_questions.php
return [
    'id' => [
        'Apa nama ibu kandung Anda?',
        'Di kota mana Anda lahir?',
        'Apa nama sekolah dasar Anda?',
        'Apa makanan favorit Anda?',
        'Apa nama hewan peliharaan pertama Anda?',
        'Siapa nama guru favorit Anda?',
        'Apa warna favorit Anda?',
        'Di mana Anda bertemu pasangan Anda?',
        'Apa nomor rumah masa kecil Anda?',
        'Apa nama jalan tempat Anda dibesarkan?',
    ],
    'en' => [
        'What is your mother\'s maiden name?',
        'In what city were you born?',
        'What is the name of your first school?',
        'What is your favorite food?',
        'What was your first pet\'s name?',
        'Who was your favorite teacher?',
        'What is your favorite color?',
        'Where did you meet your spouse?',
        'What was your childhood home number?',
        'What street did you grow up on?',
    ]
];
```

### First Time Setup Flow

#### Step 1: Registration/First Login
```blade
<!-- resources/views/auth/setup-security.blade.php -->
<form method="POST" action="{{ route('security.setup') }}">
    @csrf
    
    <h2>Setup Pertanyaan Keamanan</h2>
    <p>Pilih 2 pertanyaan untuk reset password di masa depan</p>
    
    <!-- Question 1 -->
    <div>
        <label>Pertanyaan 1</label>
        <select name="security_question_1" required>
            <option value="">-- Pilih Pertanyaan --</option>
            @foreach(config('security_questions.id') as $index => $question)
                <option value="{{ $index }}">{{ $question }}</option>
            @endforeach
        </select>
        
        <label>Jawaban</label>
        <input type="text" name="security_answer_1" required 
               placeholder="Jawaban Anda (case-insensitive)">
    </div>
    
    <!-- Question 2 -->
    <div>
        <label>Pertanyaan 2</label>
        <select name="security_question_2" required>
            <option value="">-- Pilih Pertanyaan --</option>
            @foreach(config('security_questions.id') as $index => $question)
                <option value="{{ $index }}">{{ $question }}</option>
            @endforeach
        </select>
        
        <label>Jawaban</label>
        <input type="text" name="security_answer_2" required
               placeholder="Jawaban Anda (case-insensitive)">
    </div>
    
    <button type="submit">Simpan</button>
</form>
```

#### Step 2: Save Security Questions
```php
// SecurityController.php
public function setupSecurityQuestions(Request $request)
{
    $validated = $request->validate([
        'security_question_1' => 'required|integer|min:0|max:9',
        'security_answer_1' => 'required|string|min:2|max:255',
        'security_question_2' => 'required|integer|min:0|max:9|different:security_question_1',
        'security_answer_2' => 'required|string|min:2|max:255',
    ]);
    
    $user = auth()->user();
    
    // Hash answers (case-insensitive)
    $user->update([
        'security_question_1' => $validated['security_question_1'],
        'security_answer_1' => Hash::make(strtolower(trim($validated['security_answer_1']))),
        'security_question_2' => $validated['security_question_2'],
        'security_answer_2' => Hash::make(strtolower(trim($validated['security_answer_2']))),
    ]);
    
    return redirect()->route('dashboard')
        ->with('success', 'Pertanyaan keamanan berhasil disimpan');
}
```

### Password Reset Flow

#### Step 1: Enter Email
```blade
<!-- resources/views/auth/forgot-password.blade.php -->
<form method="POST" action="{{ route('password.verify-email') }}">
    @csrf
    
    <h2>Lupa Password?</h2>
    <p>Masukkan email Anda untuk verifikasi</p>
    
    <input type="email" name="email" required 
           placeholder="email@example.com">
    
    <button type="submit">Lanjutkan</button>
</form>
```

#### Step 2: Answer Security Questions
```php
// PasswordResetController.php
public function showSecurityQuestions(Request $request)
{
    $request->validate(['email' => 'required|email']);
    
    $user = User::where('email', $request->email)->firstOrFail();
    
    $questions = config('security_questions.id');
    
    return view('auth.security-questions', [
        'email' => $user->email,
        'question_1' => $questions[$user->security_question_1],
        'question_2' => $questions[$user->security_question_2],
    ]);
}
```

```blade
<!-- resources/views/auth/security-questions.blade.php -->
<form method="POST" action="{{ route('password.verify-answers') }}">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">
    
    <h2>Jawab Pertanyaan Keamanan</h2>
    
    <div>
        <label>{{ $question_1 }}</label>
        <input type="text" name="answer_1" required>
    </div>
    
    <div>
        <label>{{ $question_2 }}</label>
        <input type="text" name="answer_2" required>
    </div>
    
    <button type="submit">Verifikasi</button>
</form>
```

#### Step 3: Verify Answers & Reset Password
```php
// PasswordResetController.php
public function verifyAnswers(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'answer_1' => 'required|string',
        'answer_2' => 'required|string',
    ]);
    
    $user = User::where('email', $request->email)->firstOrFail();
    
    // Verify answers (case-insensitive)
    $answer1Match = Hash::check(
        strtolower(trim($request->answer_1)), 
        $user->security_answer_1
    );
    
    $answer2Match = Hash::check(
        strtolower(trim($request->answer_2)), 
        $user->security_answer_2
    );
    
    if (!$answer1Match || !$answer2Match) {
        return back()
            ->withErrors(['answers' => 'Jawaban tidak sesuai'])
            ->withInput();
    }
    
    // Generate temporary token (store in session)
    session([
        'password_reset_email' => $user->email,
        'password_reset_verified' => true,
        'password_reset_expires' => now()->addMinutes(15)
    ]);
    
    return redirect()->route('password.reset-form');
}

public function showResetForm()
{
    if (!session('password_reset_verified') || 
        session('password_reset_expires') < now()) {
        return redirect()->route('password.request')
            ->withErrors(['expired' => 'Sesi verifikasi expired']);
    }
    
    return view('auth.reset-password');
}

public function resetPassword(Request $request)
{
    if (!session('password_reset_verified')) {
        abort(403);
    }
    
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);
    
    $user = User::where('email', session('password_reset_email'))->firstOrFail();
    
    $user->update([
        'password' => Hash::make($request->password)
    ]);
    
    // Clear session
    session()->forget(['password_reset_email', 'password_reset_verified', 'password_reset_expires']);
    
    // Log activity
    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'password_reset',
        'model_type' => 'user',
        'description' => 'Password reset via security questions',
        'ip_address' => $request->ip(),
    ]);
    
    return redirect()->route('login')
        ->with('success', 'Password berhasil direset. Silakan login.');
}
```

---

## 4. Simple Notification System

### Notification Helper (No Jobs, No Queue)
```php
// app/Helpers/NotificationHelper.php
class NotificationHelper
{
    /**
     * Send simple notification to user(s)
     */
    public static function send($userIds, $type, $title, $message, $link = null)
    {
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }
        
        $notifications = [];
        $now = now();
        
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'is_read' => false,
                'created_at' => $now,
            ];
        }
        
        DB::table('notifications')->insert($notifications);
    }
    
    /**
     * Send to role
     */
    public static function sendToRole($role, $type, $title, $message, $link = null)
    {
        $userIds = User::where('role', $role)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();
        
        self::send($userIds, $type, $title, $message, $link);
    }
    
    /**
     * Get unread count for user
     */
    public static function unreadCount($userId)
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
```

### Notification Types (Simple Enum)
```php
// app/Enums/NotificationType.php
enum NotificationType: string
{
    case LOW_STOCK = 'low_stock';
    case TRANSFER_REQUEST = 'transfer_request';
    case TRANSFER_APPROVED = 'transfer_approved';
    case TRANSFER_REJECTED = 'transfer_rejected';
    case MAINTENANCE_DUE = 'maintenance_due';
    case DISPOSAL_REQUEST = 'disposal_request';
    case SYSTEM_ALERT = 'system_alert';
}
```

### Usage Examples
```php
// When creating transfer request
NotificationHelper::sendToRole(
    'manager',
    'transfer_request',
    'Transfer Request Baru',
    "{$user->name} mengajukan transfer {$commodity->name}",
    route('transfers.show', $transfer->id)
);

// When stock is low (called from Observer or Scheduler)
NotificationHelper::sendToRole(
    'admin',
    'low_stock',
    'Stok Rendah',
    "{$commodity->name} hanya tersisa {$commodity->quantity} unit",
    route('commodities.show', $commodity->id)
);
```

### Simple Notification Bell (Mobile-Friendly)
```blade
<!-- resources/views/components/notification-bell.blade.php -->
<div x-data="{ open: false, unread: {{ NotificationHelper::unreadCount(auth()->id()) }} }" 
     class="relative">
    
    <!-- Bell Button -->
    <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
        </svg>
        
        <!-- Badge -->
        <span x-show="unread > 0" 
              class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
              x-text="unread > 99 ? '99+' : unread">
        </span>
    </button>
    
    <!-- Dropdown (Mobile-Optimized) -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-80 max-w-screen bg-white rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
        
        <!-- Header -->
        <div class="p-4 border-b flex justify-between items-center sticky top-0 bg-white">
            <h3 class="font-semibold">Notifikasi</h3>
            <a href="{{ route('notifications.mark-all-read') }}" 
               class="text-sm text-blue-600 hover:underline">
                Tandai Semua Dibaca
            </a>
        </div>
        
        <!-- List -->
        @forelse(auth()->user()->notifications()->latest()->limit(10)->get() as $notif)
        <a href="{{ $notif->link ?? '#' }}" 
           class="block p-4 hover:bg-gray-50 border-b {{ $notif->is_read ? 'opacity-60' : 'bg-blue-50' }}">
            
            <div class="flex items-start gap-3">
                <!-- Icon based on type -->
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                            {{ $notif->type === 'low_stock' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600' }}">
                    @if($notif->type === 'low_stock')
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/>
                        </svg>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ $notif->title }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $notif->message }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $notif->created_at->diffForHumans() }}
                    </p>
                </div>
                
                <!-- Unread indicator -->
                @if(!$notif->is_read)
                <div class="flex-shrink-0">
                    <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                </div>
                @endif
            </div>
        </a>
        @empty
        <div class="p-8 text-center text-gray-500">
            <p>Tidak ada notifikasi</p>
        </div>
        @endforelse
        
        <!-- Footer -->
        <div class="p-3 border-t text-center sticky bottom-0 bg-white">
            <a href="{{ route('notifications.index') }}" 
               class="text-sm text-blue-600 hover:underline">
                Lihat Semua
            </a>
        </div>
    </div>
</div>
```

---

## 5. Mobile-First Responsive Design

### Base Layout (Tailwind CSS)
```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#3b82f6">
    <title>{{ config('app.name') }}</title>
    
    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- PWA meta -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icon-192.png">
</head>
<body class="bg-gray-50 text-gray-900">
    
    <!-- Mobile Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo + Title -->
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="mr-3 p-2 rounded-md lg:hidden">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 5h14M3 10h14M3 15h14"/>
                        </svg>
                    </button>
                    
                    <h1 class="text-lg font-semibold truncate">
                        {{ config('app.name') }}
                    </h1>
                </div>
                
                <!-- Right Actions -->
                <div class="flex items-center gap-2">
                    <x-notification-bell />
                    
                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100">
                            <img src="{{ auth()->user()->avatar ?? '/default-avatar.png' }}" 
                                 alt="Avatar" 
                                 class="w-8 h-8 rounded-full">
                            <span class="hidden sm:inline text-sm">
                                {{ auth()->user()->name }}
                            </span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100">
                                Profile
                            </a>
                            <a href="{{ route('security.edit') }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100">
                                Keamanan
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Mobile Sidebar (Slide-over) -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:leave="transition ease-in duration-200"
         class="fixed inset-0 z-50 lg:hidden">
        
        <!-- Backdrop -->
        <div @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl">
            <div class="p-4">
                <h2 class="text-lg font-semibold">Menu</h2>
            </div>
            
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" 
                   class="block px-4 py-3 hover:bg-gray-100">
                    Dashboard
                </a>
                <a href="{{ route('commodities.index') }}" 
                   class="block px-4 py-3 hover:bg-gray-100">
                    Barang
                </a>
                <a href="{{ route('transfers.index') }}" 
                   class="block px-4 py-3 hover:bg-gray-100">
                    Transfer
                </a>
                <a href="{{ route('maintenance.index') }}" 
                   class="block px-4 py-3 hover:bg-gray-100">
                    Maintenance
                </a>
                @can('viewAny', App\Models\User::class)
                <a href="{{ route('users.index') }}" 
                   class="block px-4 py-3 hover:bg-gray-100">
                    Users
                </a>
                @endcan
            </nav>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>
    
    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="fixed bottom-0 inset-x-0 bg-white border-t lg:hidden z-40">
        <div class="flex justify-around">
            <a href="{{ route('dashboard') }}" 
               class="flex-1 flex flex-col items-center py-3 text-xs {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Home
            </a>
            <a href="{{ route('commodities.index') }}" 
               class="flex-1 flex flex-col items-center py-3 text-xs {{ request()->routeIs('commodities.*') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                Barang
            </a>
            <a href="{{ route('commodities.create') }}" 
               class="flex-1 flex flex-col items-center py-3 text-xs text-gray-600">
                <div class="w-12 h-12 -mt-6 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                    </svg>
                </div>
                Tambah
            </a>
            <a href="{{ route('transfers.index') }}" 
               class="flex-1 flex flex-col items-center py-3 text-xs {{ request()->routeIs('transfers.*') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
                </svg>
                Transfer
            </a>
            <a href="{{ route('profile.show') }}" 
               class="flex-1 flex flex-col items-center py-3 text-xs {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                </svg>
                Profile
            </a>
        </div>
    </nav>
    
    <!-- Add bottom padding to prevent content from being hidden by bottom nav on mobile -->
    <div class="h-16 lg:hidden"></div>
    
    <script>
        // Alpine.js global state
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebarOpen', false);
        });
    </script>
</body>
</html>
```

### Performance Optimizations
```php
// config/view.php - Enable view caching
'compiled' => env('VIEW_COMPILED_PATH', realpath(storage_path('framework/views'))),

// .env
VIEW_COMPILED_PATH=/path/to/compiled/views
```

```javascript
// vite.config.js - Optimize build
export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs'],
                }
            }
        },
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
            }
        }
    }
});
```

```css
/* resources/css/app.css - Purge unused Tailwind */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
```

---

## 6. Simplified Role System

### No Spatie Package - Built-in Enum
```php
// app/Enums/UserRole.php
enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case STAFF = 'staff';
    case VIEWER = 'viewer';
    
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::MANAGER => 'Manager',
            self::STAFF => 'Staff',
            self::VIEWER => 'Viewer',
        };
    }
    
    public function permissions(): array
    {
        return match($this) {
            self::ADMIN => ['*'], // All permissions
            self::MANAGER => ['commodities.*', 'transfers.approve', 'maintenance.*', 'reports.*'],
            self::STAFF => ['commodities.view', 'commodities.create', 'transfers.create', 'maintenance.view'],
            self::VIEWER => ['*.view'],
        };
    }
}
```

### Simple Authorization Helper
```php
// app/Helpers/Auth.php
class Auth
{
    public static function can($action, $resource): bool
    {
        $user = auth()->user();
        $role = UserRole::from($user->role);
        
        // Admin bypass
        if ($role === UserRole::ADMIN) {
            return true;
        }
        
        $permissions = $role->permissions();
        
        // Check wildcard
        if (in_array('*', $permissions)) {
            return true;
        }
        
        // Check exact match
        $permission = "{$resource}.{$action}";
        if (in_array($permission, $permissions)) {
            return true;
        }
        
        // Check resource wildcard
        if (in_array("{$resource}.*", $permissions)) {
            return true;
        }
        
        // Check action wildcard
        if (in_array("*.{$action}", $permissions)) {
            return true;
        }
        
        return false;
    }
}
```

### Blade Directive
```php
// app/Providers/AppServiceProvider.php
Blade::if('can', function ($action, $resource) {
    return Auth::can($action, $resource);
});
```

### Usage
```blade
@can('create', 'commodities')
    <a href="{{ route('commodities.create') }}">Tambah Barang</a>
@endcan

@can('approve', 'transfers')
    <button>Approve</button>
@endcan
```

---

## 7. Quick Implementation Checklist

### Week 1: Foundation
- [ ] Laravel installation
- [ ] Database setup (8 tables)
- [ ] Auth scaffolding (Breeze)
- [ ] Security questions system
- [ ] Basic layout (mobile-first)

### Week 2: Core Features
- [ ] Commodity CRUD
- [ ] Image upload (single/multiple)
- [ ] Search & filter
- [ ] Simple role authorization

### Week 3: Additional Features
- [ ] Transfer system
- [ ] Maintenance logs
- [ ] Disposal requests
- [ ] Simple notifications

### Week 4: Polish
- [ ] Responsive testing
- [ ] Performance optimization
- [ ] Activity logging
- [ ] Reports (basic)

### Week 5: Testing & Deploy
- [ ] Manual testing
- [ ] Bug fixes
- [ ] Documentation
- [ ] Deployment

---

## 8. Key Differences from Full Version

| Feature | Full Version | Simplified Version |
|---------|--------------|-------------------|
| **Database Tables** | 20+ tables | 8 tables |
| **Authentication** | OTP, 2FA, Email verification | Security questions only |
| **Roles & Permissions** | Spatie package, complex | Simple enum, built-in |
| **Notifications** | Queue, multi-channel, real-time | Direct insert, database only |
| **File Storage** | S3, CDN | Local storage |
| **Reports** | Advanced, scheduled | Basic, on-demand |
| **API** | Full REST API | Optional, minimal |
| **Testing** | Unit, Feature, Browser | Manual only |
| **Caching** | Redis, complex | File cache, minimal |
| **Audit** | Detailed, searchable | Basic activity log |

---

**Document Version**: 1.0  
**Philosophy**: Simple, Fast, Mobile-First  
**Target**: Small to medium inventory (< 10,000 items)  
**Status**: Ready for Implementation
