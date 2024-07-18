<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $table = 'seasons';

    protected $fillable = [
        'season',
        'url',
    ];

    protected $primaryKey = 'season';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = true;
}
