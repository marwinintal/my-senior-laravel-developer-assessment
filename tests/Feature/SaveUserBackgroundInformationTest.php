<?php

namespace Tests\Feature;

use App\Events\UserSaved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class SaveUserBackgroundInformationTest extends TestCase
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
     * Test that the listener saves user details correctly.
     *
     * @return void
     */
    public function test_listener_saves_user_details()
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

        // Fake the UserSaved event
        Event::fake();

        // // Dispatch the UserSaved event
        event(new UserSaved($user));

        // // Assert that the event was dispatched
        Event::assertDispatched(UserSaved::class);

        // // Refresh the database to ensure any listeners have saved their changes
        $this->assertDatabaseHas('details', [
            'key' => "Full Name",
            'value' => $user->fullname,
            'type' => 'bio',
            'user_id' => $user->id,
        ]);
    }
}
