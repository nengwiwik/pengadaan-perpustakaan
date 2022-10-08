<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
  use HasFactory, SoftDeletes;

  protected $guarded = [];
  protected $dates = [
    'invoice_date',
    'verified_date',
    'paid_date',
    'cancelled_date',
  ];
  protected $casts = [
    'status' => InvoiceStatus::class,
  ];
}
