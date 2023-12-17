<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bed_type extends Model
{
    use HasFactory;
    protected $table = 'bed_type';
    protected $fillable = [
        'id',
        'name',
    ];
}
