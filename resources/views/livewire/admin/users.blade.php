<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>User List</strong></h1>
                </div>
            </div>

        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a wire:navigate href="{{ route('admin.create.user') }}"
                class="btn btn-primary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">New User</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if (session('alert'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('alert') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-secondary font-w">
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Branch</th>
                            <th>User Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->branch?->name ?? '-' }}</td>
                                <td>{{ !is_null($user->roles->first()) ? \IdentityAndAccessContracts\Enums\Role::from($user->roles->first()->name)->displayName() : '-' }}
                                </td>
                                <td>

                                <div class="btn-group">
                                    <a wire:navigate href="{{ route('admin.edit.user', ['user' => $user->id]) }}"
                                        type="button" class="btn btn-primary">Edit</a>
                                    <button type="button"
                                        class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a data-toggle="modal" data-toggle="modal" data-toggle="modal"
                                        data-target="#deleteModal{{ $user->id }}" class="dropdown-item"
                                            href="#">Delete</a>
                                    </div>
                                    <!-- Delete Modal -->
                                    <div wire:ignore.self class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModal{{ $user->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModal{{ $user->id }}Label">Delete {{ $user->first_name }} {{ $user->last_name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this user?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button wire:click="delete('{{ $user->id }}')" type="button"
                                                        class="btn btn-danger">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Delete Modal -->
                                </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" align="center">No users found.</td>
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

@script
    <script>
        $wire.on('close-modal', () => {
            $('.modal').modal('hide');
        });
    </script>
@endscript
