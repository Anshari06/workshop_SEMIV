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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps();
            $table->string('catatan')->nullable();
            $table->foreignId('id_menu')->constrained('menus')->onDelete('restrict');
            $table->foreignId('id_pesanan')->constrained('pesanans')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
