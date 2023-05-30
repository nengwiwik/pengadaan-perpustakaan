<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procurement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = [
        'invoice_date',
        'verified_date',
        'paid_date',
        'cancelled_date',
    ];

    const STATUS_PROSES = "Proses";
    const STATUS_BARU = "Baru";
    const STATUS_AKTIF = "Aktif";
    const STATUS_INVOICE = "Invoice";
    const STATUS_SELESAI = "Selesai";
    const STATUS_DITOLAK = "Ditolak";

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function procurement_books(): HasMany
    {
        return $this->hasMany(ProcurementBook::class);
    }
}