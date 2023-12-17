<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room_type';

    protected $fillable = [
        'id',
        'hotel_id',
        'name',
        'status',
        'slug',
        'image',
        'bed_type_id',
        'price',
        'max_adults',
        'max_children',
        // 'room_count',
    ];

    public function bedtype()
    {
        return $this->belongsTo(bed_type::class, 'bed_type_id');
    }
}
