<?php

namespace App\Notifications;

use App\Models\RoommateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RoommateRequestAcceptedNotification extends Notification
{
    use Queueable;

    public RoommateRequest $request;

    public function __construct(RoommateRequest $request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Roommate request accepted',
            'message' => 'Your roommate request has been accepted.',
            'type' => 'request_accepted',
            'related_id' => $this->request->id,
            'route' => route('roommate-match.index'),
        ];
    }
}
