<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class area extends Model
{
    use HasFactory;

    protected $table = 'area';
    protected $fillable = [
        'id',
        'name',
    ];

    public function area()
    {
        return $this->hasMany(Places::class, 'area_id');
    }
}
