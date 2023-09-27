<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_widgets extends Model
{
    use HasFactory;

    protected $table = 'room_type_widgets';

    protected $fillable = [
        'room_type_id',
        'widgets_id'
    ];
}
