<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheikh extends Model
{
    /** @use HasFactory<\Database\Factories\EnseignantFactory> */
    use HasFactory;

    public function halaqas()
    {
        return $this->hasMany(Halaqa::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class, 'cheikh_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
