<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserLoggedInNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Welcome Back',
            'message' => 'You logged in successfully! Start searching for roommates.',
            'type' => 'login',
            'related_id' => null,
            'route' => route('dashboard'),
        ];
    }
}
