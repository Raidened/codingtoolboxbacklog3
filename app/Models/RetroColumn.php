<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RetroColumn extends Model
{
    protected $table = 'retros_columns';
    protected $fillable = ['retro_id', 'name', 'position'];

    public function retro(): BelongsTo
    {
        return $this->belongsTo(Retro::class);
    }

    public function data(): HasMany
    {
        return $this->hasMany(RetroData::class, 'retros_column_id', 'id')->orderBy('position');
    }
}
