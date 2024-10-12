<?php

namespace Tests\Unit\Unit\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
        User::factory()->count(30)->create();
        $paginatedUsers = $this->userService->list();

        // Assert: Check that the result is a LengthAwarePaginator instance and has 15 items per page
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $paginatedUsers);
        $this->assertEquals(10, $paginatedUsers->count());

        // Assert structure of the paginated result
        $responseArray = $paginatedUsers->toArray();
        $this->assertArrayHasKey('data', $responseArray);
        $this->assertArrayHasKey('current_page', $responseArray);
        $this->assertArrayHasKey('last_page', $responseArray);
        $this->assertArrayHasKey('per_page', $responseArray);
        $this->assertArrayHasKey('total', $responseArray);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        $userData = [
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => '',
            'lastname' => 'Doe',
            'suffixname' => 'Phd',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        $user = $this->userService->store($userData);

        // Assert: Check that the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['firstname'], $user->firstname);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        // Create a user instance in the database
        $user = User::factory()->create([
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => '',
            'lastname' => 'Doe',
            'suffixname' => 'Phd',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        // Act: Attempt to find the user using the UserService
        $foundUser = $this->userService->find($user->id);

        // Assert: Check that the found user matches the created user
        $this->assertNotNull($foundUser);
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($user->firstname, $foundUser->firstname);
        $this->assertEquals($user->email, $foundUser->email);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrange: Create a user in the database
        $user = User::factory()->create([
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => '',
            'lastname' => 'Doe',
            'suffixname' => 'Phd',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        // Define new data for the update
        $updateData = [
            'firstname' => 'Jane Doe',
            'email' => 'janedoe@example.com',
        ];

        // Act: Attempt to update the user using the UserService
        $updatedUser = (object)$this->userService->update($user->id, $updateData);

        // Assert: Check that the user's information was updated
        $this->assertNotNull($updatedUser);
        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals($user->id, $updatedUser->id);
        $this->assertEquals('Jane Doe', $updatedUser->firstname);
        $this->assertEquals('janedoe@example.com', $updatedUser->email);

        // Verify the changes are reflected in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'Jane Doe',
            'email' => 'janedoe@example.com',
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        // Arrange: Create a user in the database
        $user = User::factory()->create([
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => '',
            'lastname' => 'Doe',
            'suffixname' => 'Phd',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        // Act: Soft delete the user using the UserService
        $softDeleted = (bool)$this->userService->destroy($user->id);

        // Assert: Verify that the user was soft deleted
        $this->assertTrue($softDeleted);
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        // Optionally, check that the user is no longer retrieved in regular queries
        $this->assertNull(User::find($user->id));

        // But can still be retrieved with the trashed method
        $trashedUser = User::withTrashed()->find($user->id);
        $this->assertNotNull($trashedUser);
        $this->assertNotNull($trashedUser->deleted_at);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        // Arrange: Create soft-deleted users in the database
        $trashedUsers = User::factory()->count(5)->create()->each(function ($user) {
            $user->delete(); // Soft delete each user
        });

        // Act: Get the paginated list of trashed users
        $retrievedTrashedUsers = $this->userService->listTrashed();

        // Assert: Check that the number of retrieved trashed users matches the created users
        $this->assertCount(5, $retrievedTrashedUsers);

        // Assert that each user in the retrieved list is trashed
        foreach ($retrievedTrashedUsers as $user) {
            $this->assertTrue($user->trashed());
        }
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user(): void
    {
        // Arrange: Create and soft delete a user
        $user = User::factory()->create();
        $user->delete(); // Soft delete the user

        // Act: Restore the soft-deleted user using the UserService
        $restored = (bool)$this->userService->restore($user->id);
        // Assert: Check that the user was restored
        $this->assertTrue($restored);

        // Verify the user is no longer trashed
        $this->assertNotNull($user = User::find($user->id));

        /**
         * Ensures that the restored user is no longer considered trashed
         */
        $this->assertFalse($user->trashed(), 'The user should not be trashed after restoration.');
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        // Arrange: Create and soft delete a user
        $user = User::factory()->create();
        $user->delete(); // Soft delete the user

        // Assert that the user is indeed trashed
        $this->assertTrue($user->trashed(), 'The user should be trashed.');
        // Act: Permanently delete the soft-deleted user using the UserService
        $deleted = (bool)$this->userService->delete($user->id);

        // Assert: Check that the user was permanently deleted
        $this->assertTrue($deleted, 'The user deletion should return true.');

        // Verify the user is not found in the database
        $this->assertNull(User::withTrashed()->find($user->id), 'The user should not exist after permanent deletion.');
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Create a fake image
        $photo = UploadedFile::fake()->image('profile.jpg');

        // Act: Upload the photo using the UserService
        $path = $this->userService->upload($user, $photo);

        // Assert: Check that the photo was uploaded
        Storage::disk('public')->assertExists($path);

        // Assert: Check that the user's photo_path was updated
        $this->assertEquals($path, $user->photo);
    }
}
