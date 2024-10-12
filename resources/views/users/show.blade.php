<x-app-layout>
    <div class="container mt-5">
        <h1 class="mb-4">User Details</h1>
        @if ($user->avatar)
            <div class="profile-image text-center my-3">
                <img src="{{ asset($user->avatar) }}" alt="Profile Photo" class="rounded" width="150">
            </div>
        @else
            <p>No profile photo available.</p>
        @endif

        <div class="card">
            <div class="card-header">
                <h2>{{ $user->name }}</h2>
            </div>
            <div class="card-body">
                <p><strong>Prefix:</strong> {{ $user->prefixname }} </p>
                <p><strong>Name:</strong> {{ $user->fullname }} </p>
                <p><strong>Suffix:</strong> {{ $user->suffixname }} </p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Created At:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
                <p><strong>Updated At:</strong> {{ $user->updated_at->format('Y-m-d') }}</p>
            </div>
        </div>

        <a href="{{ route('users.index') }}" class="btn btn-primary mt-3">Back to Users List</a>
    </div>
</x-app-layout>
