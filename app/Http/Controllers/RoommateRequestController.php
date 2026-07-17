<?php

namespace App\Http\Controllers;

use App\Models\RoommateMatch;
use App\Models\RoommateRequest;
use App\Models\User;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;

class RoommateRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();

        // Mark request-related notifications as read
        $user->unreadNotifications->filter(function ($n) {
            $type = data_get($n->data, 'type');
            return in_array($type, ['roommate_request', 'request_accepted', 'request_rejected', 'roommate_removed']);
        })->each(function($n) {
            $n->markAsRead();
        });

        $incoming = RoommateRequest::with(['sender.studentProfile', 'sender.studentPreference'])
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'incoming_page');

        $outgoing = RoommateRequest::with(['receiver.studentProfile', 'receiver.studentPreference'])
            ->where('sender_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'outgoing_page');

        $accepted = RoommateRequest::with(['sender.studentProfile', 'receiver.studentProfile'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'accepted_page');

        $rejected = RoommateRequest::with(['sender.studentProfile', 'receiver.studentProfile'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'rejected')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'rejected_page');

        $cancelled = RoommateRequest::with(['sender.studentProfile', 'receiver.studentProfile'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'cancelled')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'cancelled_page');

        $pendingIncomingCount = RoommateRequest::where('receiver_id', $user->id)->where('status', 'pending')->count();
        $pendingOutgoingCount = RoommateRequest::where('sender_id', $user->id)->where('status', 'pending')->count();
        $latestRequest = RoommateRequest::with(['sender.studentProfile', 'receiver.studentProfile'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->first();

        return view('roommate_requests.index', [
            'incoming' => $incoming,
            'outgoing' => $outgoing,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'cancelled' => $cancelled,
            'pendingIncomingCount' => $pendingIncomingCount,
            'pendingOutgoingCount' => $pendingOutgoingCount,
            'latestRequest' => $latestRequest,
            'compatibility' => $compatibility,
        ]);
    }

    public function history(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();

        $history = RoommateRequest::with(['sender.studentProfile', 'receiver.studentProfile'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('roommate_requests.history', [
            'history' => $history,
            'compatibility' => $compatibility,
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'integer', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:250'],
        ]);

        $user = $request->user();
        $receiverId = (int) $request->input('receiver_id');

        if ($receiverId === $user->id) {
            return back()->with('error', 'You cannot send a request to yourself.');
        }

        $receiver = User::findOrFail($receiverId);

        if ($user->hasActiveRoommate()) {
            return back()->with('error', 'You already have an active roommate match and cannot send a new request.');
        }

        if ($receiver->hasActiveRoommate()) {
            return back()->with('error', 'This student already has an active roommate match.');
        }

        $existingMatchBetweenPair = RoommateMatch::active()
            ->where(function ($query) use ($user, $receiverId) {
                $query->where(function ($pair) use ($user, $receiverId) {
                    $pair->where('student_one_id', $user->id)
                        ->where('student_two_id', $receiverId);
                })->orWhere(function ($pair) use ($user, $receiverId) {
                    $pair->where('student_one_id', $receiverId)
                        ->where('student_two_id', $user->id);
                });
            })
            ->exists();

        if ($existingMatchBetweenPair) {
            return back()->with('error', 'You already have an active roommate match with this student.');
        }

        $duplicate = RoommateRequest::where('sender_id', $user->id)
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'You already have a pending request for this student.');
        }

        $roommateRequest = RoommateRequest::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'status' => 'pending',
            'message' => $request->input('message'),
        ]);

        $receiver->notify(new \App\Notifications\NewRoommateRequestNotification($roommateRequest));

        return back()->with('status', 'Roommate request sent successfully.');
    }

    public function accept(Request $request, CompatibilityService $compatibility, $id)
    {
        $user = $request->user();
        $roommateRequest = RoommateRequest::with(['sender', 'receiver'])->find($id);

        if (! $roommateRequest) {
            return back()->with('error', 'Roommate request not found.');
        }

        if ($roommateRequest->receiver_id !== $user->id) {
            return back()->with('error', 'You are not authorized to accept this request.');
        }

        if ($roommateRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be accepted.');
        }

        if ($roommateRequest->sender_id === $roommateRequest->receiver_id) {
            return back()->with('error', 'You cannot accept your own request.');
        }

        if ($roommateRequest->sender->hasActiveRoommate()) {
            return back()->with('error', 'The requester already has an active roommate match.');
        }

        if ($roommateRequest->receiver->hasActiveRoommate()) {
            return back()->with('error', 'You already have an active roommate match.');
        }

        $existingMatchBetweenPair = RoommateMatch::active()
            ->where(function ($query) use ($roommateRequest) {
                $query->where(function ($pair) use ($roommateRequest) {
                    $pair->where('student_one_id', $roommateRequest->sender_id)
                        ->where('student_two_id', $roommateRequest->receiver_id);
                })->orWhere(function ($pair) use ($roommateRequest) {
                    $pair->where('student_one_id', $roommateRequest->receiver_id)
                        ->where('student_two_id', $roommateRequest->sender_id);
                });
            })
            ->exists();

        if ($existingMatchBetweenPair) {
            return back()->with('error', 'This request already has an active roommate match.');
        }

        $roommateRequest->status = 'accepted';
        $roommateRequest->accepted_at = now();
        $roommateRequest->save();

        $roommateRequest->sender->notify(new \App\Notifications\RoommateRequestAcceptedNotification($roommateRequest));

        $compatibilityScore = $compatibility->calculateScore($roommateRequest->sender, $roommateRequest->receiver);

        if (class_exists(RoommateMatch::class)) {
            RoommateMatch::create([
                'student_one_id' => $roommateRequest->sender_id,
                'student_two_id' => $roommateRequest->receiver_id,
                'compatibility_score' => $compatibilityScore,
                'matched_at' => now(),
                'status' => 'active',
            ]);
        } else {
            // Match will be created in Feature 8
        }

        return back()->with('status', 'Roommate request accepted successfully.');
    }

    public function reject(Request $request, $id)
    {
        $user = $request->user();
        $roommateRequest = RoommateRequest::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $roommateRequest->update(['status' => 'rejected']);
        $roommateRequest->sender->notify(new \App\Notifications\RoommateRequestRejectedNotification($roommateRequest));

        return back()->with('status', 'Roommate request rejected.');
    }

    public function cancel(Request $request, $id)
    {
        $user = $request->user();
        $roommateRequest = RoommateRequest::where('id', $id)
            ->where('sender_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $roommateRequest->update(['status' => 'cancelled']);

        return back()->with('status', 'Roommate request cancelled.');
    }
}
