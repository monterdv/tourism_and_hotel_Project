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
        Schema::create('booking_hotel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_type_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('hotel_id');
            $table->string('name');
            $table->string('phone_number');
            $table->string('email');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price');
            $table->integer('totalDays');
            $table->unsignedBigInteger('user_id');
            $table->enum('status_booking', ['upcoming', 'in_progress', 'completed'])->default('upcoming');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('room_type_id')->references('id')->on('room_type');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_hotel');
    }
};
