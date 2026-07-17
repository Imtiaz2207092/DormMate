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
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if (empty($data['message']) && !$request->hasFile('image')) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Cannot send an empty message.'], 422);
            }
            return back()->withErrors(['message' => 'Cannot send an empty message.']);
        }

        $user = $request->user();
        $conversation = Conversation::findOrFail($data['conversation_id']);

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => isset($data['message']) ? trim($data['message']) : null,
            'image' => $imagePath,
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
                'image' => $msg->image ? asset('storage/' . $msg->image) : null,
                'created_at' => $msg->created_at->format('h:i A'),
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
