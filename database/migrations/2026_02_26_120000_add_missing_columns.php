<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom otp_code jika belum ada
        if (!Schema::hasColumn('users', 'otp_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('otp_code', 6)->nullable();
            });
        }

        // Tambah kolom id_google jika belum ada
        if (!Schema::hasColumn('users', 'id_google')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('id_google', 256)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'otp_code')) {
                $table->dropColumn('otp_code');
            }
            if (Schema::hasColumn('users', 'id_google')) {
                $table->dropColumn('id_google');
            }
        });
    }
};
