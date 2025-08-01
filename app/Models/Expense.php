<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'amount',
        'is_paid',
        'type',
        'payment_date',
    ];

    protected $appends = ['model_type'];

    public function getModelTypeAttribute() {
        return 'App\Models\Expense';
    }

    public function notes(): MorphMany
    {
        return $this-> morphMany(Note::class, 'noteable')
            ->with('user')
            ->latest();
    }
}
