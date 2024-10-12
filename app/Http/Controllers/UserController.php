<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected UserService $userService;

    // Constructor injection of UserService
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all users
        $users = User::all();

        // Pass users to the view
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view for creating a new user
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Create a new user with validated data
            $user = new User();
            $user->prefixname = $validatedData['prefixname'];
            $user->middlename = $validatedData['middlename'];
            $user->suffixname = $validatedData['suffixname'];
            $user->firstname = $validatedData['firstname'];
            $user->lastname = $validatedData['lastname'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];
            $user->password = bcrypt($validatedData['password']); // Hash the password

            // Check for file upload
            if ($request->hasFile('photo')) {
                // $file = $request->file('photo');
                // $uploadPhoto = $this->uploadPhoto($file, $user);
                $path = $this->userService->upload($user, $request->file('photo'));
                $user->photo = $path;
            }

            // Save the new user to the database
            $user->save();

            // Redirect to users index with a success message
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Return the view for editing the user
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            // Check for file upload
            if ($request->hasFile('photo')) {
                $this->userService->upload($user, $request->file('photo'));
            }

            // Find changed fields by comparing input with the current model data
            $changes = array_diff_assoc($request->validated(), $user->only(array_keys($request->validated())));

            // If there are changes, update the user
            if (!empty($changes)) {
                $user->update($changes);
                return redirect()->route('users.index')->with('success', 'User updated successfully.');
            }
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete(); // Delete the user

            // Redirect to users index with a success message
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->route('users.index')->with('error', 'Failed to delete user. Please try again.');
        }
    }

    public function softDeleted()
    {
        // Fetch all soft-deleted users
        $softDeletedUsers = User::onlyTrashed()->get();

        // Return the view for listing soft-deleted users
        return view('users.trashed', compact('softDeletedUsers'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id); // Ensure the user exists in the trashed state
        $user->restore(); // Restore the user
        return redirect()->route('users.softDeleted')->with('success', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id); // Ensure the user exists in the trashed state
        $user->forceDelete(); // Permanently delete the user
        return redirect()->route('users.softDeleted')->with('success', 'User permanently deleted.');
    }

    private function uploadPhoto($file, $user = null)
    {
        // Validate file upload
        if ($file->isValid()) {
            // Delete existing photo if it exists
            if ($user->photo && file_exists(public_path('photos/' . $user->photo))) {
                unlink(public_path('photos/' . $user->photo));
            }

            // Create a unique filename
            $filename = time() . '_' . $file->getClientOriginalName();

            // Move the file to the public/photos directory
            $success = $file->move(public_path('photos'), $filename);

            // Check if the file was moved successfully
            if (!$success) {
                Log::error("The photo failed to upload.");
            }

            // return the uploaded filename
            return $filename;
        } else {
            Log::warning("Uploaded file is not valid.");
        }
    }
}
