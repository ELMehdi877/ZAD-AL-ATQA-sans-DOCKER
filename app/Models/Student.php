<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'nombre_hifz',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function halaqas()
    {
        return $this->belongsToMany(Halaqa::class, 'memberships', 'student_id', 'halaqa_id')
            ->withPivot('statut', 'student_id', 'halaqa_id')
            ->withTimestamps();
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'participations', 'student_id', 'competition_id')
            ->withPivot('cheikh_id', 'note_tajwid', 'note_hifz', 'remarque', 'statut')
            ->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

}
