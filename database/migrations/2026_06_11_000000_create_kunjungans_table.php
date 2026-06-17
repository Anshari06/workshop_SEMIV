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
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_toko', 8);
            $table->string('nama_toko', 100);
            $table->double('lat_toko');
            $table->double('lng_toko');
            $table->double('accuracy_toko');
            $table->double('lat_sales');
            $table->double('lng_sales');
            $table->double('accuracy_sales');
            $table->double('jarak');
            $table->double('threshold');
            $table->double('threshold_efektif');
            $table->enum('status', ['diterima', 'ditolak'])->default('ditolak');
            $table->timestamps();

            $table->foreign('barcode_toko')->references('barcode')->on('lokasi_toko')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};