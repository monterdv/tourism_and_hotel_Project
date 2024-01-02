<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;
    protected $table = 'categories_post';
    protected $fillable = [
        'id', 
        'name', 
        'created_at', 
        'updated_at'
    ];

}
