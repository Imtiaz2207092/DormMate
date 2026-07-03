<?php

namespace App\Notifications;

use App\Models\RoommateMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RoommateRemovedNotification extends Notification
{
    use Queueable;

    public RoommateMatch $match;
    public string $endedByName;

    public function __construct(RoommateMatch $match, string $endedByName)
    {
        $this->match = $match;
        $this->endedByName = $endedByName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Roommate removed',
            'message' => sprintf('Your roommate relationship has been removed by %s.', $this->endedByName),
            'type' => 'roommate_removed',
            'related_id' => $this->match->id,
            'route' => route('roommate-match.index'),
        ];
    }
}
