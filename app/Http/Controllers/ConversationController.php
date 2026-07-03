<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $selectedId = $request->query('conversation');

        $conversations = Conversation::with(['userOne.studentProfile', 'userTwo.studentProfile'])
            ->where(function ($q) use ($user) {
                $q->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id);
            })
            ->get()
            ->map(function ($c) use ($user) {
                $other = $c->user_one_id === $user->id ? $c->userTwo : $c->userOne;
                $c->other = $other;
                $c->latest = $c->latestMessage();
                $c->unread = $c->messages()->where('is_read', false)->where('sender_id', '!=', $user->id)->count();
                return $c;
            })
            ->sortByDesc(fn($c) => optional($c->latest)->created_at)
            ->values();

        $activeConversation = null;
        $activeOther = null;
        $activeScore = null;

        if ($selectedId && is_numeric($selectedId)) {
            $activeConversation = Conversation::with(['messages.sender', 'userOne.studentProfile', 'userTwo.studentProfile'])
                ->find($selectedId);
        }

        if ($activeConversation && $activeConversation->user_one_id !== $user->id && $activeConversation->user_two_id !== $user->id) {
            $activeConversation = null;
        }

        if (!$activeConversation && $conversations->isNotEmpty()) {
            $activeConversation = Conversation::with(['messages.sender', 'userOne.studentProfile', 'userTwo.studentProfile'])
                ->find($conversations->first()->id);
        }

        if ($activeConversation) {
            $activeConversation->update([
                'last_opened_by' => $user->id,
                'last_opened_at' => now(),
            ]);

            $activeOther = $activeConversation->user_one_id === $user->id ? $activeConversation->userTwo : $activeConversation->userOne;
            if ($user->studentProfile && $user->studentPreference && $activeOther->studentPreference) {
                $activeScore = app(CompatibilityService::class)->calculateScore($user, $activeOther);
            }
        }

        return view('messages.index', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'activeOther' => $activeOther,
            'activeScore' => $activeScore,
        ]);
    }

    public function show(Request $request, $id, CompatibilityService $compatibility)
    {
        $user = $request->user();
        $conversation = Conversation::with(['messages.sender', 'userOne.studentProfile', 'userTwo.studentProfile'])->findOrFail($id);

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $conversation->update([
            'last_opened_by' => $user->id,
            'last_opened_at' => now(),
        ]);

        // mark unread messages as read where receiver is current user
        $conversation->messages()->where('is_read', false)->where('sender_id', '!=', $user->id)->update(['is_read' => true]);

        $other = $conversation->user_one_id === $user->id ? $conversation->userTwo : $conversation->userOne;

        $score = null;
        if ($user->studentProfile && $user->studentPreference && $other->studentPreference) {
            $score = $compatibility->calculateScore($user, $other);
        }

        return view('messages.show', [
            'conversation' => $conversation,
            'other' => $other,
            'score' => $score,
        ]);
    }

    public function createOrOpen(Request $request)
    {
        $request->validate(['other_user_id' => 'required|integer']);

        $user = $request->user();
        $otherId = (int) $request->other_user_id;

        if ($user->id === $otherId) {
            return back()->with('error', 'Cannot message yourself.');
        }

        $ids = [$user->id, $otherId];
        sort($ids);

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => $ids[0],
            'user_two_id' => $ids[1],
        ]);

        return redirect()->route('messages.index', ['conversation' => $conversation->id]);
    }
}
