<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotel_paths extends Model
{
    use HasFactory;

    protected $table = 'hotel_paths';

    protected $fillable = [
        'id',
        'path',
        'hotel_id',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class); // Liên kết với mô hình Hotel
    }
}
