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
            $table->enum('prefixname', ['Mr', 'Mrs', 'Ms'])
                ->nullable()
                ->after('id');

            $table->string('firstname')
                ->after('prefixname');

            $table->string('middlename')
                ->nullable()
                ->after('firstname');

            $table->string('lastname')
                ->after('middlename');

            $table->string('suffixname')
                ->nullable()
                ->after('lastname');

            $table->string('username')
                ->unique()
                ->after('suffixname');

            $table->text('photo')
                ->nullable()
                ->after('password');

            $table->string('type')
                ->index()
                ->default('user')
                ->after('photo');
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
