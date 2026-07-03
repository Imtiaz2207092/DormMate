<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\RoommateRequest;
use App\Models\StudentPreference;
use App\Models\StudentProfile;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'university',
        'major',
        'year',
        'phone',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function studentPreference()
    {
        return $this->hasOne(StudentPreference::class);
    }

    public function sentRequests()
    {
        return $this->hasMany(RoommateRequest::class, 'sender_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(RoommateRequest::class, 'receiver_id');
    }

    public function currentRoommate()
    {
        return $this->belongsToMany(User::class, 'roommate_requests', 'sender_id', 'receiver_id')
            ->where('roommate_requests.status', 'accepted');
    }
}
