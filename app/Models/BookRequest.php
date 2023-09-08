<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookRequest extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_REQUESTED = 'requested';
    const STATUS_OWNED = 'owned';
    const STATUS_OFF = 'off';

    const STATUSES = [
        self::STATUS_REQUESTED,
        self::STATUS_OWNED,
        self::STATUS_OFF,
    ];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
