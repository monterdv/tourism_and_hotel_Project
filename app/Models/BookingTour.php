<?php

namespace App\Models;

// use Database\Seeders\payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\payment;

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
        'status_payment',
        'status_booking',
        'created_at',
    ];
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function time()
    {
        return $this->belongsTo(tour_Time::class, 'tourTime_id');
    }
    public function payments()
    {
        return $this->belongsTo(payment::class, 'payment_id');
    }
    public function slots()
    {
        return $this->hasMany(slot_tour::class, 'bookings_tour_id');
    }
   
}
