<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a temporary column to hold data
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('temp_email_verified_at')->nullable();
        });

        // Copy the data from the old column to the temporary column
        DB::table('users')->update(['temp_email_verified_at' => DB::raw('email_verified_at')]);

        // Drop the old column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });

        // Add the email_verified_at column back after the role_id column (or any other column you choose)
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('remember_token');
        });

        // Copy data back from the temporary column to the email_verified_at column
        DB::table('users')->update(['email_verified_at' => DB::raw('temp_email_verified_at')]);

        // Drop the temporary column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('temp_email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
