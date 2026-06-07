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
        Schema::create('gears', function (Blueprint $table) {
        $table->id();
        $table->string('nama_gear');
        $table->string('merk');
        $table->integer('harga_4jam');
        $table->integer('harga_6jam');
        $table->integer('harga_12jam');
        $table->integer('harga_24jam');
        $table->string('jaminan');
        $table->string('status');
        $table->integer('stok')->default(0); 
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gears');
    }
};
