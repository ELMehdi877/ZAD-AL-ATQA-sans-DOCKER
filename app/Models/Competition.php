<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionFactory> */
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'statut',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'participations', 'competition_id', 'student_id')
            ->withPivot('cheikh_id', 'note_tajwid', 'note_hifz', 'remarque', 'statut')
            ->withTimestamps();
    }
}
