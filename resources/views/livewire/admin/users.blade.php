<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">User List</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a wire:navigate href="{{ route('admin.create.user') }}" class="btn btn-primary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">New User</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Branch</th>
                            <th>User Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>Male</td>
                                <td>Admin</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" align="center">No users found.</td>
                            </tr>
                        @endforelse 
                        <!-- Add more user data rows as needed -->
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>

</div>