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
        Schema::create('sissoin_users', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->string('refesh_token');
            $table->dateTime('token_expried');
            $table->dateTime('refesh_token_expried');
            $table->bigInteger('User_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sissoin_users');
    }
};
