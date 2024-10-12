<x-app-layout>
    <div class="container mt-5">
        <a href="{{ route('users.index') }}" class="btn btn-primary mb-3">Users List</a>

        <h4 class="mb-4">Soft Deleted Users</h4>

        <!-- Display success or error messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($softDeletedUsers as $user)
                    <tr>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->deleted_at }}</td>
                        <td>
                            <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                            </form>

                            <form action="{{ route('users.forceDelete', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to permanently delete this user?');">Delete Permanently</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No soft deleted users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
