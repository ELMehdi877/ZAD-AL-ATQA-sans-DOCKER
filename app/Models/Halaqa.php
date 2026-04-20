<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Halaqa extends Model
{
    /** @use HasFactory<\Database\Factories\HalaqaFactory> */
    use HasFactory;

    protected $fillable = [
        'nom_halaqa',
        'capacite',
        'cheikh_id'
    ];

    public function cheikh()
    {
        return $this->belongsTo(User::class, 'cheikh_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'memberships', 'halaqa_id', 'student_id')
            ->withPivot('statut', 'student_id', 'halaqa_id')
            ->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
