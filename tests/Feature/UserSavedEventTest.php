<?php

namespace Tests\Feature;

use App\Events\UserSaved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserSavedEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

     /**
     * Test that the UserSaved event triggers the listener to save user details correctly.
     *
     * @return void
     */
    public function test_user_saved_event_triggers_listener_and_saves_details()
    {
        // Create a user instance
        $user = User::factory()->create([
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => 'Henry',
            'lastname' => 'Doe',
            'suffixname' => 'Phd',
            'username' => 'johndepp',
            'email' => 'johndepp@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Dispatch the UserSaved event
        event(new UserSaved($user));

        // Assert that the details were saved in the database
        $this->assertDatabaseHas('details', [
            'user_id' => $user->id,
            'key' => "Full Name",
            'value' => $user->fullname,
            'type' => 'bio',
        ]);
    }
}
