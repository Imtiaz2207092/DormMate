<?php

namespace App\Notifications;

use App\Models\RoommateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RoommateRequestRejectedNotification extends Notification
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
            'title' => 'Roommate request rejected',
            'message' => 'Your roommate request has been rejected.',
            'type' => 'request_rejected',
            'related_id' => $this->request->id,
            'route' => route('roommate-requests.index'),
        ];
    }
}
