<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipationFactory> */
    use HasFactory;

    protected $fillable = [
        'cheikh_id',
        'student_id',
        'competition_id',
        'note_tajwid',
        'note_hifz',
        'remarque',
        'statut',

    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function cheikh()
    {
        return $this->belongsTo(User::class, 'cheikh_id');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
