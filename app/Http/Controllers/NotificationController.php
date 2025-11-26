<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi.
     */
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markRead(DatabaseNotification $notification): RedirectResponse
    {
        $notification->markAsRead();

        // Redirect ke action URL jika ada
        if (isset($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
