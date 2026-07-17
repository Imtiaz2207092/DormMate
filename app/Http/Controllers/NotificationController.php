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

    public function poll(Request $request, \App\Services\CompatibilityService $compatibility)
    {
        $user = $request->user();

        // 1. Calculate Top 3 matches and check for updates
        if ($user->studentProfile && $user->studentPreference) {
            $currentTop3 = $compatibility->getBestMatches($user, 3);
            $currentTop3Ids = $currentTop3->pluck('id')->toArray();
            sort($currentTop3Ids);

            $cacheKey = 'user_top3_ids_' . $user->id;
            $cachedTop3Ids = \Illuminate\Support\Facades\Cache::get($cacheKey);

            if ($cachedTop3Ids !== null) {
                if ($cachedTop3Ids !== $currentTop3Ids) {
                    $newBestMatch = $currentTop3->first();
                    if ($newBestMatch) {
                        $user->notify(new \App\Notifications\TopMatchesChangedNotification($newBestMatch->name));
                    }
                }
            }
            \Illuminate\Support\Facades\Cache::put($cacheKey, $currentTop3Ids, now()->addDays(7));
        }

        // 2. Fetch unread notifications
        $unreadCount = $user->unreadNotifications()->count();
        $unreadNotifications = $user->unreadNotifications()->latest()->get()->map(function ($notif) {
            return [
                'id' => $notif->id,
                'title' => data_get($notif->data, 'title'),
                'message' => data_get($notif->data, 'message'),
                'type' => data_get($notif->data, 'type'),
                'route' => route('notifications.redirect', $notif->id),
                'created_at_human' => $notif->created_at->diffForHumans(),
            ];
        });

        // 3. Unread messages count
        $unreadMessagesCount = \App\Models\Conversation::where(function($q) use ($user) {
            $q->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id);
        })->get()->sum(function($c) use ($user) {
            return $c->messages()->where('is_read', false)->where('sender_id', '!=', $user->id)->count();
        });

        // 4. Unread requests count
        $unreadRequestsCount = $user->unreadNotifications->filter(function ($n) {
            $type = data_get($n->data, 'type');
            return in_array($type, ['roommate_request', 'request_accepted', 'request_rejected', 'roommate_removed']);
        })->count();

        return response()->json([
            'unread_notifications_count' => $unreadCount,
            'unread_messages_count' => $unreadMessagesCount,
            'unread_requests_count' => $unreadRequestsCount,
            'notifications' => $unreadNotifications,
        ]);
    }
}
