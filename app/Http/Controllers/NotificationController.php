<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');

        $query = $user->notifications()->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        if ($search) {
            $query->where('data', 'like', "%\"title\":\"%{$search}%\"%");
        }

        $notifications = $query->paginate(15)->withQueryString();

        return view('notifications.index', [
            'notifications' => $notifications,
            'filter' => $filter,
            'search' => $search,
        ]);
    }

    public function redirect(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        $route = data_get($notification->data, 'route');
        return redirect($route ?? route('notifications.index'));
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'All notifications have been marked as read.');
    }

    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();
        return back()->with('status', 'Notification deleted.');
    }
}
