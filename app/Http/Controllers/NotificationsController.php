<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Get the count of unread notifications for the authenticated user
     */
    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $unreadCount = Auth::user()->unreadNotifications()->count();

        return response()->json(['count' => $unreadCount]);
    }

    /**
     * Display all notifications for the authenticated user
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}