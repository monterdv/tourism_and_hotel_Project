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
        'bookings_Code',
        'tour_id',
        'tourTime_id',
        'adults',
        'children',
        'total_price',
        'user_id',
        'payment_id',
        'status',
        'created_at',
    ];
}
