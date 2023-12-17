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
        Schema::create('room_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('slug');
            $table->string('image');
            $table->unsignedBigInteger('bed_type_id');
            $table->integer('price');
            $table->unsignedInteger('max_adults')->default(2);
            $table->unsignedInteger('max_children')->default(0);
            $table->timestamps();
            
            $table->foreign('bed_type_id')->references('id')->on('bed_type');
            $table->foreign('hotel_id')->references('id')->on('hotels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type');
    }
};
