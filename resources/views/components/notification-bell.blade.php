@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $notifications = auth()->user()->notifications()->latest()->limit(5)->get();
@endphp

<div class="relative" x-data="{ open: false }">
    <!-- Bell Button -->
    <button @click="open = !open" class="relative p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        @if($unreadCount > 0)
        <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-danger-500 rounded-full">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Notifikasi</h3>
            @if($unreadCount > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-primary-600 hover:underline">
                    Tandai semua dibaca
                </button>
            </form>
            @endif
        </div>

        <!-- List -->
        <div class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notification)
            <a href="{{ $notification->data['action_url'] ?? '#' }}" 
               class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 {{ $notification->read_at ? '' : 'bg-primary-50' }}">
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @if(!$notification->read_at)
                    <div class="flex-shrink-0">
                        <span class="w-2 h-2 bg-primary-500 rounded-full inline-block"></span>
                    </div>
                    @endif
                </div>
            </a>
            @empty
            <div class="px-4 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm">Tidak ada notifikasi</p>
            </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($notifications->count() > 0)
        <div class="px-4 py-3 border-t border-gray-200 text-center">
            <a href="{{ route('notifications.index') }}" class="text-sm text-primary-600 hover:underline">
                Lihat Semua Notifikasi
            </a>
        </div>
        @endif
    </div>
</div>
