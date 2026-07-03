<?php

namespace App\Http\Controllers;

use App\Models\RoommateRequest;
use App\Models\User;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoommateRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();

        $incoming = RoommateRequest::with(['sender.studentProfile', 'sender.studentPreference'])
            ->where('receiver_id', $user->id)
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

        $hasAccepted = RoommateRequest::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })->where('status', 'accepted')->exists();

        if ($hasAccepted) {
            return back()->with('error', 'You already have an active roommate.');
        }

        $receiverHasAccepted = RoommateRequest::where(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)->orWhere('receiver_id', $receiverId);
        })->where('status', 'accepted')->exists();

        if ($receiverHasAccepted) {
            return back()->with('error', 'This student already has an active roommate.');
        }

        $duplicate = RoommateRequest::where('sender_id', $user->id)
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'You already have a pending request for this student.');
        }

        RoommateRequest::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'status' => 'pending',
            'message' => $request->input('message'),
        ]);

        return back()->with('status', 'Roommate request sent successfully.');
    }

    public function accept(Request $request, $id)
    {
        $user = $request->user();
        $roommateRequest = RoommateRequest::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $senderHasAccepted = RoommateRequest::where(function ($query) use ($roommateRequest) {
            $query->where('sender_id', $roommateRequest->sender_id)->orWhere('receiver_id', $roommateRequest->sender_id);
        })->where('status', 'accepted')->exists();

        if ($senderHasAccepted) {
            return back()->with('error', 'The sender already has an active roommate.');
        }

        $receiverHasAccepted = RoommateRequest::where(function ($query) use ($roommateRequest) {
            $query->where('sender_id', $roommateRequest->receiver_id)->orWhere('receiver_id', $roommateRequest->receiver_id);
        })->where('status', 'accepted')->exists();

        if ($receiverHasAccepted) {
            return back()->with('error', 'You already have an active roommate.');
        }

        $roommateRequest->update(['status' => 'accepted']);

        return back()->with('status', 'Roommate request accepted.');
    }

    public function reject(Request $request, $id)
    {
        $user = $request->user();
        $roommateRequest = RoommateRequest::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $roommateRequest->update(['status' => 'rejected']);

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
