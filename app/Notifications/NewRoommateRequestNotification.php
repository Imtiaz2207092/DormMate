<?php

namespace App\Notifications;

use App\Models\RoommateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewRoommateRequestNotification extends Notification
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
            'title' => 'New roommate request',
            'message' => sprintf('%s sent you a roommate request.', $this->request->sender->name),
            'type' => 'roommate_request',
            'related_id' => $this->request->id,
            'route' => route('roommate-requests.index'),
        ];
    }
}
