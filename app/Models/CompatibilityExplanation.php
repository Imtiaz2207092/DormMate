<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompatibilityExplanation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matched_user_id',
        'compatibility_score',
        'ai_explanation',
    ];

    protected $casts = [
        'compatibility_score' => 'integer',
        'ai_explanation' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function matchedUser()
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }
}
