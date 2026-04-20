<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationFactory> */
    use HasFactory;
   protected $fillable = [
        'cheikh_id',
        'student_id',
        'halaqa_id',
        'du_sourate',
        'au_sourate',
        'hizb',
        'du_aya',
        'au_aya',
        'note',
        'remarque',
        'presence',
    ];

    public function cheikh()
    {
        return $this->belongsTo(User::class, 'cheikh_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function halaqa()
    {
        return $this->belongsTo(Halaqa::class);
    }
}
