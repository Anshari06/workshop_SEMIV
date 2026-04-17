<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('province_id')->nullable()->after('nama_customer');
            $table->string('province_name')->nullable()->after('province_id');
            $table->string('regency_id')->nullable()->after('province_name');
            $table->string('regency_name')->nullable()->after('regency_id');
            $table->string('village_id')->nullable()->after('regency_name');
            $table->string('village_name')->nullable()->after('village_id');
        });

        DB::statement('ALTER TABLE customer MODIFY foto_blob LONGBLOB NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE customer MODIFY foto_blob BLOB NULL');

        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn([
                'province_id',
                'province_name',
                'regency_id',
                'regency_name',
                'village_id',
                'village_name',
            ]);
        });
    }
};
