<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProcurementBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function procurement(): BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }

    public function getCoverAttribute($value)
    {
        if (is_null($value)) {
            return asset('image/book-cover.png');
        }
        if (strpos($value, 'http') === false) {
            return Storage::url($value);
        } else {
            return $value;
        }
    }
}
