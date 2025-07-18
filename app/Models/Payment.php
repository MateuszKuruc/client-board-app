<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'amount',
        'status',
        'payment_date',
    ];
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
