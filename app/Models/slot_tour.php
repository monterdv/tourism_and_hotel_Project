<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class slot_tour extends Model
{
    use HasFactory;
    protected $table = 'slot_tour';
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'passport',
        'nationality_id',
        'bookings_tour_id',
        'type',
        'created_at',
    ];
}
