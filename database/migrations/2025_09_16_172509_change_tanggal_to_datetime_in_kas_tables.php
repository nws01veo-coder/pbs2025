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
        // Change tanggal column from date to datetime in kas_masuks table
        Schema::table('kas_masuks', function (Blueprint $table) {
            $table->datetime('tanggal')->change();
        });

        // Change tanggal column from date to datetime in kas_keluars table
        Schema::table('kas_keluars', function (Blueprint $table) {
            $table->datetime('tanggal')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to date column in kas_masuks table
        Schema::table('kas_masuks', function (Blueprint $table) {
            $table->date('tanggal')->change();
        });

        // Revert back to date column in kas_keluars table
        Schema::table('kas_keluars', function (Blueprint $table) {
            $table->date('tanggal')->change();
        });
    }
};
