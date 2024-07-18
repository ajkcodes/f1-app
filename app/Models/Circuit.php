<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circuit extends Model
{
    use HasFactory;

    protected $table = 'circuits';

    protected $fillable = [
        'circuitId',
        'url',
        'circuitName',
        'location_id'
    ];

    protected $primaryKey = 'circuitId';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = true;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function races()
    {
        return $this->hasMany(Race::class, 'circuitId', 'circuitId');
    }
}
