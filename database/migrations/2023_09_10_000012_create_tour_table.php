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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('tour_Code');
            $table->string('title')->unique();
            $table->string('slug');
            $table->text('introduce');
            $table->text('schedule');
            $table->string('status');
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('vehicle_id');

            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('place_id')->references('id')->on('places');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour');
    }
};
