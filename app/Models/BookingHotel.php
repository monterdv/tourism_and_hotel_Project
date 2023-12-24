<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHotel extends Model
{
    use HasFactory;
    protected $table = 'booking_hotel';
    protected $fillable = [
        'id',
        'room_type_id',
        'room_id',
        'hotel_id',
        'name',
        'phone_number',
        'email',
        'start_date',
        'end_date',
        'price',
        'user_id',
        'totalDays',
        'status_booking',
        'created_at',
    ];
    public function numberRoom()
    {
        return $this->belongsTo(room_number::class, 'room_id');
    }

    public function room_type()
    {
        return $this->belongsTo(Room::class, 'room_type_id');
    }
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
