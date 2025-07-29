<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'client_id',
        'service_id',
        'active',
        'price',
        'type',
        'start_date',
        'end_date',
    ];

    protected $appends = ['model_type'];

    public function getModelTypeAttribute()
    {
        return 'App\Models\Project';
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function notes(): MorphMany
    {
        return $this-> morphMany(Note::class, 'noteable')
            ->with('user')
            ->latest();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
