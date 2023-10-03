<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tour_Time extends Model
{
    use HasFactory;

    protected $table = 'tours_time';

    protected $fillable = [
        'id',
        'tour_id',
        'status',
        'slots_remaining',
        'slots_booked',
        'price_adults',
        'price_children',
        'date',
    ];

    
}
