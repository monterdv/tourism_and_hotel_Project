<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room_type_amenities extends Model
{
    use HasFactory;
    protected $table = 'room_type_amenities';

    protected $fillable = [
        'room_type_id',
        'amenities_id'
    ];
}
