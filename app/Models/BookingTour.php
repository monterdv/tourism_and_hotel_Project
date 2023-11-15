<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTour extends Model
{
    use HasFactory;
    protected $table = 'bookings_tour';
    protected $fillable = [
        'id',
        'tour_id',
        'tourTime_id',
        'adults',
        'children',
        'total_price',
        'user_id',
        'created_at',
    ];

}
