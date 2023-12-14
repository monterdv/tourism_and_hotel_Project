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
            $table->string('bookings_Code');
            $table->unsignedBigInteger('tour_id');
            $table->unsignedBigInteger('tourTime_id');
            $table->integer('adults');
            $table->integer('children');
            $table->float('total_price');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_id');
            $table->enum('status_payment', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('status_booking', ['upcoming', 'in_progress', 'completed'])->default('upcoming');
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payment');
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
