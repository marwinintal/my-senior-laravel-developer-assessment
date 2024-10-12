<section>


    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password">Current Password</label>
            <input type="password" name="current_password" id="update_password_current_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="update_password_password">New Password</label>
            <input type="password" name="password" id="update_password_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control" required>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">Save</button>
            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif
        </div>
    </form>
</section>
