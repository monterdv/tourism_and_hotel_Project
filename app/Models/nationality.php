<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nationality extends Model
{
    use HasFactory;
    protected $table = 'nationality';
    protected $fillable = [
        'id',
        'name',
        'created_at',
    ];
}
