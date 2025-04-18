<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'cohort_id',
        'teacher_id',
        'title',
        'value',
        'evaluation_date',
        'description',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'evaluation_date' => 'date',
        'value' => 'float',
    ];

    /**
     * Relation avec l'étudiant qui a reçu la note
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'enseignant qui a attribué la note
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relation avec la promotion à laquelle appartient l'étudiant
     */
    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }
}
