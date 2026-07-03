<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sleep_schedule',
        'wake_up_time',
        'study_habit',
        'cleanliness',
        'smoking',
        'noise_tolerance',
        'guests_frequency',
        'room_temperature',
        'music_preference',
        'lights_preference',
        'introvert_extrovert',
        'sleep_with_light',
        'pets',
        'hobbies',
        'languages',
        'additional_notes',
    ];

    protected $casts = [
        'smoking' => 'boolean',
        'sleep_with_light' => 'boolean',
        'pets' => 'boolean',
        'wake_up_time' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
