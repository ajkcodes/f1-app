<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'results';

    protected $fillable = [
        'race_id',
        'number',
        'position',
        'positionText',
        'points',
        'driverId',
        'constructorId',
        'grid',
        'laps',
        'status',
        'time_millis',
        'time'
    ];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function constructor()
    {
        return $this->belongsTo(Constructor::class);
    }
}
