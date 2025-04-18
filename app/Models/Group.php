<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $table = 'groups';
    protected $fillable = ['cohort_id', 'batch_name', 'name', 'description', 'is_auto_generated', 'generation_params'];
    protected $casts = [
        'is_auto_generated' => 'boolean',
        'generation_params' => 'array',
    ];

    /**
     * Get the cohort that owns the group
     */
    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class);
    }

    /**
     * Get the users in this group
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_groups')
                    ->withTimestamps();
    }
}
