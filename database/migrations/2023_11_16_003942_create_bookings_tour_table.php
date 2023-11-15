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
        Schema::create('bookings_tour', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');
            $table->unsignedBigInteger('tourTime_id');
            $table->integer('adults');
            $table->integer('children');
            $table->float('total_price');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours');
            $table->foreign('tourTime_id')->references('id')->on('tours_time');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings_tour');
    }
};
