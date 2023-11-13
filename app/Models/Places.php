<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    use HasFactory;

    protected $table = 'places';

    protected $fillable = ['id', 'slug', 'area_id', 'country', 'prominent', 'image', 'created_at', 'updated_at'];
    public function area()
    {
        return $this->hasMany(Places::class, 'area_id');
    }
}
