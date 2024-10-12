<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class CreateUsersTableTest extends TestCase
{
    use RefreshDatabase; // Use this to migrate the database for the test

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_has_expected_columns()
    {
        // Run the migration
        $this->artisan('migrate');

        // Check the users table for expected columns
        $this->assertTrue(Schema::hasTable('users'));

        $columns = Schema::getColumnListing('users');

        // Assert that the columns exist
        $this->assertContains('id', $columns);
        $this->assertContains('prefixname', $columns);
        $this->assertContains('firstname', $columns);
        $this->assertContains('middlename', $columns);
        $this->assertContains('lastname', $columns);
        $this->assertContains('suffixname', $columns);
        $this->assertContains('username', $columns);
        $this->assertContains('email', $columns);
        $this->assertContains('password', $columns);
        $this->assertContains('photo', $columns);
        $this->assertContains('type', $columns);
        $this->assertContains('remember_token', $columns);
        $this->assertContains('email_verified_at', $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
        $this->assertContains('deleted_at', $columns);
    }

    /** @test */
    public function it_has_correct_column_types()
    {
        // Run the migration
        $this->artisan('migrate');

        // Check the column types
        $this->assertEquals('bigint', Schema::getColumnType('users', 'id'));
        $this->assertEquals('enum', Schema::getColumnType('users', 'prefixname'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'firstname'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'middlename'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'lastname'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'suffixname'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'username'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'email'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'password'));
        $this->assertEquals('text', Schema::getColumnType('users', 'photo'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'type'));
        $this->assertEquals('varchar', Schema::getColumnType('users', 'remember_token'));
        $this->assertEquals('timestamp', Schema::getColumnType('users', 'email_verified_at'));
        $this->assertEquals('timestamp', Schema::getColumnType('users', 'created_at'));
        $this->assertEquals('timestamp', Schema::getColumnType('users', 'updated_at'));
        $this->assertEquals('timestamp', Schema::getColumnType('users', 'deleted_at'));
    }
}
