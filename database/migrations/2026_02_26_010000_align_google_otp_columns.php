<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique()->nullable();
            }

            if (!Schema::hasColumn('users', 'id_google')) {
                $table->string('id_google', 256)->nullable();
            }

            if (!Schema::hasColumn('users', 'otp')) {
                $table->string('otp', 6)->nullable();
            }
        });

        if (Schema::hasColumn('users', 'google_id') && Schema::hasColumn('users', 'id_google')) {
            DB::table('users')
                ->whereNull('id_google')
                ->update(['id_google' => DB::raw('google_id')]);
        }

        if (Schema::hasColumn('users', 'otp_code') && Schema::hasColumn('users', 'otp')) {
            DB::table('users')
                ->whereNull('otp')
                ->update(['otp' => DB::raw('otp_code')]);
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'otp_code')) {
                $table->dropColumn('otp_code');
            }
            if (Schema::hasColumn('users', 'otp_expires_at')) {
                $table->dropColumn('otp_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'otp_code')) {
                $table->string('otp_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable();
            }

            if (Schema::hasColumn('users', 'otp')) {
                $table->dropColumn('otp');
            }
            if (Schema::hasColumn('users', 'id_google')) {
                $table->dropColumn('id_google');
            }
        });
    }
};
