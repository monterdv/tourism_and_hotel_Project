<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart_tour extends Model
{
    use HasFactory;
    protected $table = 'cart_tour';
    protected $fillable = [
        'id',
        'user_id',
        'tour_id',
        'tours_time_id',
        'adults',
        'children',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function tours_times()
    {
        return $this->belongsTo(tour_Time::class, 'tours_time_id');
    }
}
