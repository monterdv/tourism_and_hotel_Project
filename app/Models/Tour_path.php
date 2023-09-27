<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour_path extends Model
{
    use HasFactory;

    protected $table = 'tour_path';

    protected $fillable = [
        'path',
        'tour_id',
    ];
}
