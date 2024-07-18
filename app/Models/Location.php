<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'lat',
        'long',
        'locality',
        'country'
    ];

    public $timestamps = true;

    public function circuits()
    {
        return $this->hasMany(Circuit::class);
    }
}
