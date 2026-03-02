<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pastikan kolom penting ada
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->unique();
            }
            
            if (!Schema::hasColumn('users', 'id_google')) {
                $table->string('id_google', 256)->nullable()->unique();
            }
            
            if (!Schema::hasColumn('users', 'otp_code')) {
                $table->string('otp_code', 6)->nullable();
            }

            // Hapus kolom yang redundan
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
            
            if (Schema::hasColumn('users', 'otp')) {
                $table->dropColumn('otp');
            }
            
            if (Schema::hasColumn('users', 'otp_expires_at')) {
                $table->dropColumn('otp_expires_at');
            }
            
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('users', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable();
            }
        });
    }
};
