<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Delegate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'park_id',
        'post',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function park(): belongsTo
    {
        return $this->belongsTo(Park::class);
    }
}
