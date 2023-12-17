<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours';

    protected $fillable = [
        'title',
        'slug',
        'status',
        'schedule',
        'tour_Code',
        'place_id',
        'introduce',
        'vehicle_id',
        'prominent',
        'category_id',
        'duration',
    ];

    public function place()
    {
        return $this->belongsTo(Places::class, 'place_id');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');
    }

    public function tourPaths()
    {
        return $this->hasMany(Tour_path::class, 'tour_id');
    }

    public function tourTime()
    {
        return $this->hasMany(tour_Time::class, 'tour_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
