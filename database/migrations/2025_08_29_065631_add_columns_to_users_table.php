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
            $table->string('phone')->after('email_verified_at')->unique();
            $table->timestamp('phone_verified_at')->after('phone')->nullable();
            $table->string('otp')->after('phone_verified_at')->nullable();
            $table->boolean('is_active')->default(false)->after('otp');
            $table->boolean('is_lock')->default(false)->after('is_active');
            $table->boolean('is_delete')->default(false)->after('is_lock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
