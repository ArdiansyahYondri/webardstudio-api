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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('tanggal_sewa');
            $table->dateTime('tenggat_waktu');
            $table->dateTime('waktu_kembali')->nullable();
            $table->integer('total_harga')->default(0);
            $table->integer('nominal_denda')->default(0);
            $table->enum('status_penyewaan', ['pending', 'aktif', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
