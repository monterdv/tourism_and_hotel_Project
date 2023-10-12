<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotels';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'address',
        'status',
        'introduce',
        'star_rating',
        'place_id',
        'checkout_time',
        'checkin_time',
    ];

    public function images()
    {
        return $this->hasMany(hotel_paths::class);
    }
    public function place()
    {
        return $this->belongsTo(Places::class, 'place_id');
    }

    public function hotelPaths()
    {
        return $this->hasMany(hotel_paths::class, 'hotel_id');
    }

}
