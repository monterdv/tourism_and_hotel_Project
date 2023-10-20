<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_paths', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->unsignedBigInteger('hotel_id');
            $table->timestamps();
            
            $table->foreign('hotel_id')->references('id')->on('hotels');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_paths');
    }
};
