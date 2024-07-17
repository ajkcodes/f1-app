<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constructor extends Model
{
    use HasFactory;

    protected $table = 'constructors';

    protected $fillable = [
        'constructorId',
        'url',
        'name',
        'nationality'
    ];

    protected $primaryKey = 'constructorId';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = true;
}
