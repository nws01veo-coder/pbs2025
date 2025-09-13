<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_time',
        'platform'
    ];

    protected $dates = [
        'activity_time'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
