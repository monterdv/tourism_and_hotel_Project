<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bedtype extends Model
{
    use HasFactory;
    protected $table = 'bed_type';

    protected $fillable = [
        'id',
        'name',
    ];
}
