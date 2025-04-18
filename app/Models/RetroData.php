<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetroData extends Model
{
    protected $table = 'retros_data';
    protected $fillable = ['retros_column_id', 'name', 'description', 'position'];

    public function column(): BelongsTo
    {
        return $this->belongsTo(RetroColumn::class, 'retros_column_id');
    }
}
