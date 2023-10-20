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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug');
            $table->string('address');
            $table->string('status');
            $table->text('introduce');
            $table->unsignedTinyInteger('star_rating')->nullable();
            $table->unsignedBigInteger('place_id');
            $table->time('checkin_time');
            $table->time('checkout_time');
            $table->timestamps();

            $table->foreign('place_id')->references('id')->on('places');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
