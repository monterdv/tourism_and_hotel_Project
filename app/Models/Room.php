<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room_types';

    protected $fillable = [
        'id',
        'hotel_id',
        'slug',
        'name',
        'status',
        'description',
        'image',
        'base_price',
        'max_adults',
        'max_children',
        'room_count',
    ];

    public function widgets()
    {
        return $this->belongsToMany(Widget::class);
    }
}
