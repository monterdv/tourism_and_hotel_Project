<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = ['id', 'title', 'slug', 'prominent', 'introduce', 'image', 'categories_id', 'created_at', 'updated_at'];
}
