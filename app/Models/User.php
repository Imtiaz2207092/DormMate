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
        'is_admin',
        'active',
        'user_type',
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
            'is_admin' => 'boolean',
            'active' => 'boolean',
            'user_type' => 'string',
        ];
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->user_type === 'admin';
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

    public function roommateMatchesAsOne()
    {
        return $this->hasMany(RoommateMatch::class, 'student_one_id');
    }

    public function roommateMatchesAsTwo()
    {
        return $this->hasMany(RoommateMatch::class, 'student_two_id');
    }

    public function activeRoommateMatch()
    {
        return RoommateMatch::active()->forUser($this->id)->first();
    }

    public function hasActiveRoommate(): bool
    {
        return RoommateMatch::active()->forUser($this->id)->count() >= 1;
    }

    public function currentRoommate(): ?User
    {
        $match = $this->activeRoommateMatch();

        return $match ? $match->otherStudent($this) : null;
    }

    // Conversations where the user is participant
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    public function hasConversationWith(User $user): bool
    {
        $ids = [$this->id, $user->id];
        sort($ids);
        return Conversation::where(function ($q) use ($ids) {
            $q->where('user_one_id', $ids[0])->where('user_two_id', $ids[1]);
        })->exists();
    }

    public function getConversationWith(User $user): ?\App\Models\Conversation
    {
        $ids = [$this->id, $user->id];
        sort($ids);
        return Conversation::where('user_one_id', $ids[0])->where('user_two_id', $ids[1])->first();
    }
}
