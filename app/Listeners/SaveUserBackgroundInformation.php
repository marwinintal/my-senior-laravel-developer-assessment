<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SaveUserBackgroundInformation
{
    protected $userService;

    /**
     * Create the event listener.
     */
    public function __construct(UserService $userService)
    {
        // Inject UserService and assign to a property
        $this->userService = $userService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        Log::info('@SaveUserBackgroundInformation');
        // Access the user instance from the event
        $user = $event->user;
        $details = [
            "Full Name" => $user->fullname,
            "Middle Initial" => $user->middleinitial,
            "Avatar" => $user->avatar,
            "Gender" => $user->genderFromPrefix,
        ];

        // Use the UserService to perform actions related to user's background information
        $this->userService->saveUserDetails($user, $details);
    }

}
