<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    protected $appends = ['model_type'];

    public function getModelTypeAttribute()
    {
        return 'App\Models\Payment';
    }
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function notes(): MorphMany
    {
        return $this-> morphMany(Note::class, 'noteable')
            ->with('user')
            ->latest();
    }
}
