<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'meeting_name',
        'url',
        'cheikh_id',
        'halaqa_id',
    ];

    public function cheikh()
    {
        return $this->belongsTo(User::class, 'cheikh_id');
    }

    public function teacher()
    {
        return $this->cheikh();
    }

    public function halaqa()
    {
        return $this->belongsTo(Halaqa::class);
    }
}