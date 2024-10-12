<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="btn btn-primary">Email Password Reset Link</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
