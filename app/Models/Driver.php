<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'driverId',
        'code',
        'url',
        'givenName',
        'familyName',
        'dateOfBirth',
        'nationality'
    ];

    protected $primaryKey = 'driverId';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'dateOfBirth' => 'date',
    ];

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
