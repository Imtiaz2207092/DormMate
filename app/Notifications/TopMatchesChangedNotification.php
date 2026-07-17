<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TopMatchesChangedNotification extends Notification
{
    use Queueable;

    protected string $bestMatchName;

    public function __construct(string $bestMatchName)
    {
        $this->bestMatchName = $bestMatchName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Top Matches Updated',
            'message' => sprintf('Your top roommate matches have changed. Your new best match is %s!', $this->bestMatchName),
            'type' => 'top_matches_changed',
            'related_id' => null,
            'route' => route('dashboard'),
        ];
    }
}
