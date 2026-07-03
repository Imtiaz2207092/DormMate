<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string|max:1000',
        ]);

        $user = $request->user();

        $conversation = Conversation::findOrFail($data['conversation_id']);

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => trim($data['message']),
            'is_read' => false,
        ]);

        $receiverId = $conversation->user_one_id === $user->id ? $conversation->user_two_id : $conversation->user_one_id;
        $shouldNotify = $conversation->last_opened_by !== $receiverId || ! $conversation->last_opened_at || $conversation->last_opened_at->lt(now()->subMinutes(5));

        if ($shouldNotify) {
            $conversation->user_one_id === $receiverId
                ? $conversation->userOne->notify(new \App\Notifications\NewMessageNotification($msg))
                : $conversation->userTwo->notify(new \App\Notifications\NewMessageNotification($msg));
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('j M Y, H:i'),
                'sender_initial' => strtoupper(substr($user->name, 0, 1)),
            ]);
        }

        return redirect()->route('messages.index', ['conversation' => $conversation->id])->with('status', 'Message sent.');
    }

    public function markAsRead(Request $request)
    {
        $data = $request->validate(['conversation_id' => 'required|integer']);
        $user = $request->user();

        $conversation = Conversation::findOrFail($data['conversation_id']);
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $conversation->messages()->where('is_read', false)->where('sender_id', '!=', $user->id)->update(['is_read' => true]);

        return back();
    }
}
