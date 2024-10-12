<x-app-layout>
    <div class="container mt-5">
        <!-- Add button to create a new user -->
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create User</a>
        <a href="{{ route('users.softDeleted') }}" class="btn btn-danger mb-3">Deleted User</a>

        <h4 class="mb-4">Users List</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><a href="{{ route('users.show', $user->id) }}">{{ $user->fullname }}</a></td> <!-- Link to user details -->
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a> <!-- Edit button -->
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
