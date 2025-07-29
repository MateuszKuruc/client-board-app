<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'is_paid',
        'type',
        'payment_date',
    ];

    protected $appends = ['model_type'];

    public function getModelTypeAttribute() {
        return 'App\Models\Expense';
    }
}
