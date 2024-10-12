<x-app-layout>
    <div class="container mt-5">
    <h1 class="mb-4">Edit User</h1>

    <!-- Display success or error messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf <!-- CSRF token for security -->
                @method('PUT') <!-- Method spoofing for PUT request -->

                <div class="form-group">
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label for="prefixname">Prefix {{ $user->prefixname }}</label>
                                <select id="prefixname" name="prefixname" class="form-select form-control">
                                    <option value="Mr" {{ old('prefixname', $user->prefixname) == 'Mr' ? 'selected' : '' }}>Mr</option>
                                    <option value="Mrs" {{ old('prefixname', $user->prefixname) == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                    <option value="ms" {{ old('prefixname', $user->prefixname) == 'Ms' ? 'selected' : '' }}>Ms</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" name="firstname" id="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label for="middlename">Middle Name</label>
                                <input type="text" name="middlename" id="middlename" class="form-control" value="{{ old('middlename', $user->middlename) }}">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label for="suffixname">Suffix</label>
                                <input type="text" name="suffixname" id="suffixname" class="form-control" value="{{ old('suffixname', $user->suffixname) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="username">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">@</div>
                        </div>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ old('Username', $user->username) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" name="photo" id="photo">
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users List</a>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
