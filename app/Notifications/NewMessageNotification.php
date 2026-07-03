<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $sender = $this->message->sender;

        return [
            'title' => 'New message',
            'message' => sprintf('New message from %s.', $sender->name),
            'type' => 'new_message',
            'related_id' => $this->message->conversation_id,
            'route' => route('messages.index', ['conversation' => $this->message->conversation_id]),
        ];
    }
}
