<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room_number extends Model
{
    use HasFactory;
    protected $table = 'rooms';

    protected $fillable = [
        'room_type_id',
        'status',
        'number_of_rooms',
    ];
}
