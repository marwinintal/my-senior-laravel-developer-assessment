<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Mock the current time to a fixed date
        Carbon::setTestNow('2024-10-11 12:00:00');
    }

    public function test_users_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/users');

        $response->assertOk();
    }

    public function test_user_information_can_be_updated(): void
    {
        // Create a user to update
        $userToUpdate = User::factory()->create([
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => '',
            'lastname' => 'Doe',
            'suffixname' => 'Phd',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        // Create an authenticated user with the necessary role
        $authUser = User::factory()->create([
            'prefixname' => 'Mr',
            'firstname' => 'admin',
            'middlename' => '',
            'lastname' => 'Doe',
            'suffixname' => 'Phd',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        // Data to update
        $data = [
            'prefixname' => 'Mrs',
            'firstname' => 'Johny',
            'middlename' => '',
            'lastname' => 'Depp',
            'suffixname' => 'Phd',
            'username' => 'johndepp',
            'email' => 'johndepp@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Check that the user is not authenticated before acting
        $this->assertFalse(auth()->check(), 'User should not be authenticated before actingAs.');

        // Act as the authenticated user
        $response = $this->actingAs($authUser)->patch(route('users.update', $userToUpdate->id), $data);
        // Now check if the user is authenticated
        $this->assertTrue(auth()->check(), 'User should be authenticated after actingAs.');
    }
}
