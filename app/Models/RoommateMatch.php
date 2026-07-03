<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoommateMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_one_id',
        'student_two_id',
        'compatibility_score',
        'matched_at',
        'status',
        'ended_at',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function studentOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_one_id');
    }

    public function studentTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_two_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('student_one_id', $userId)->orWhere('student_two_id', $userId);
    }

    public function otherStudent(User $user)
    {
        return $this->student_one_id === $user->id ? $this->studentTwo : $this->studentOne;
    }
}
