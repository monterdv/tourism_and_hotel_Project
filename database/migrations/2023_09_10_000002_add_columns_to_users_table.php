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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->integer('wallet')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('status_id');

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('status_id')->references('id')->on('users_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropColumn('avatar');
            // $table->dropColumn('wallet');
            // $table->dropForeign(['status_id']);
            // $table->dropColumn('status_id');
            // $table->dropColumn('department');
        });
    }
};
