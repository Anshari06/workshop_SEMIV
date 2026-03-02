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
            // Tambah kolom email jika belum ada
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique()->nullable();
            }
            
            // Tambah kolom google_id jika belum ada
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique();
            }
            
            // Drop kolom name jika ada (karena pakai username)
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email', 'google_id']);
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable();
            }
        });
    }
};
