<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="prefixname">Prefix {{ $user->prefixname }}</label>
                        <select id="prefixname" name="prefixname" class="form-select form-control">
                            <option value="Mr" {{ old('prefixname', $user->prefixname) == 'Mr' ? 'selected' : '' }}>Mr</option>
                            <option value="Mrs" {{ old('prefixname', $user->prefixname) == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                            <option value="ms" {{ old('prefixname', $user->prefixname) == 'Ms' ? 'selected' : '' }}>Ms</option>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="suffixname">Suffix</label>
                        <input type="text" name="suffixname" id="suffixname" class="form-control" value="{{ old('suffixname', $user->suffixname) }}">
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" name="middlename" id="middlename" class="form-control" value="{{ old('middlename', $user->middlename) }}">
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div> --}}

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">Save</button>

            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif
        </div>
    </form>
</section>
