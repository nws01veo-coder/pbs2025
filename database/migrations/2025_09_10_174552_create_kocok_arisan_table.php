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
        Schema::create('kocok_arisan', function (Blueprint $table) {
            $table->id();
            $table->integer('periode'); // Periode ke berapa dalam tahun tersebut
            $table->string('bulan'); // Nama bulan (Januari, Februari, dll)
            $table->integer('tahun'); // Tahun kocok arisan
            $table->unsignedBigInteger('anggota_id'); // ID anggota yang menang
            $table->enum('status', ['pending', 'active', 'completed'])->default('active');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('anggota_id')->references('id')->on('anggotas')->onDelete('cascade');
            
            // Unique constraint untuk memastikan tidak ada duplikasi bulan dan tahun
            $table->unique(['bulan', 'tahun']);
            
            // Index untuk performance
            $table->index(['tahun', 'periode']);
            $table->index('anggota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kocok_arisan');
    }
};
