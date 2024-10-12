<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\User;
use App\Services\UserServiceInterface;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class UserService implements UserServiceInterface
{
    /**
     * Define the validation rules for the model.
     *
     * @param  int $id
     * @return array
     */
    public function rules($request, $user = null)
    {
        $rules = [
            'prefixname' => ['string', 'max:5'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'suffixname' => ['nullable', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];

        // Conditionally add unique rule for `email` only if it has changed
        if ($user) {
            if ($request['username'] !== $user->username) {
                $rules['username'] = 'required|unique:users,username,' . $user->id;
            } else {
                $rules['username'] = 'required';
            }

            // Conditionally add unique rule for `email` only if it has changed
            if ($request['email'] !== $user->email) {
                $rules['email'] = 'required|unique:users,email,' . $user->id;
            } else {
                $rules['email'] = 'required';
            }
        } else {
            $rules['username'] = 'required|unique:users,username,' . User::class;
            $rules['email'] = 'required|unique:users,email,' . User::class;
        }

        return $rules;
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list(int $perPage = 10, int $currentPage = 1): LengthAwarePaginator
    {
        return User::paginate($perPage, ['*'], 'page', $currentPage);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes)
    {
        return User::create([
            'prefixname' => $attributes['prefixname'],
            'middlename' => $attributes['email'],
            'suffixname' => $attributes['suffixname'],
            'firstname' => $attributes['firstname'],
            'lastname' => $attributes['lastname'],
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => $this->hash($attributes['password']),
        ]);
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id): ?User
    {
        // Code goes brrrr.
        return User::findOrFail($id);
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes)
    {
        // Code goes brrrr.
        $user = User::find($id);
        if ($user) {
            $user->update($attributes);
        }

        return $user;
    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function destroy($id): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }

        return false;
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed()
    {
        return User::onlyTrashed()->get();
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id): bool
    {
        $user = User::onlyTrashed()->find($id);
        if ($user) {
            return $user->restore() ? true : false;
        }

        return false;
    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
        $user = User::onlyTrashed()->find($id);
        if ($user) {
            return $user->forceDelete();
        }

        return false;
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        // Code goes brrrr.
        return $key;
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(User $user, UploadedFile $file)
    {
        // Code goes brrrr.
        // Validate that the uploaded file is an image
        $this->validatePhoto($file);

        // Delete existing photo if it exists
        if ($user->photo && file_exists(public_path('photos/' . $user->photo))) {
            unlink(public_path('photos/' . $user->photo));
        }

        // Store the photo and get the path
        $path = $file->store('photos', 'public');

        // Optionally, update the user's photo path in the database
        // $user->photo = $path;
        // $user->save();

        return $path;
    }

    /**
     * Validate the uploaded photo.
     *
     * @param \Illuminate\Http\UploadedFile $photo
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validatePhoto($photo): void
    {
        if (!$photo->isValid() || !str_starts_with($photo->getMimeType(), 'image/')) {
            throw new \InvalidArgumentException('The uploaded file is not a valid image.');
        }
    }

    public function saveUserDetails(User $user, array $details, $type = 'bio')
    {
        Log::info("@saveBackgroundInformation");
        foreach ($details as $detail_key => $detail_value) {
            $detail = Detail::create([
                'key' => $detail_key,
                'value' => $detail_value,
                'type' => $type,
                'user_id' => $user->id,
            ]);

            Log::info("UserService@saveBackgroundInformation: successfully saved detail[{$detail->id}] of the user[{$user->id}]");
        }
    }
}
