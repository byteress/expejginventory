<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Branch List</strong></h1>
                </div>
            </div>
        </div>
    </div>


    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a wire:navigate href="{{ route('admin.create.branch') }}" class="btn btn-primary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">New Branch</span>
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 offset-md-2">
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
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->address ?? '-' }}</td>
                            <td>{{ $branch->phone ?? '-' }}</td>
                            <td align = "center">
                                <div class="btn-group">
                                    <a wire:navigate href="{{ route('admin.edit.branch', ['branch' => $branch->id]) }}"
                                        type="button" class="btn btn-primary">Edit</a>
                                    <button type="button"
                                        class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a data-toggle="modal" data-target="#deleteModal{{ $branch->id }}" class="dropdown-item"
                                            href="#">Delete</a>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div wire:ignore.self class="modal fade" id="deleteModal{{ $branch->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModal{{ $branch->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModal{{ $branch->id }}Label">Delete Branch</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this branch?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button wire:click="delete('{{ $branch->id }}')" type="button" class="btn btn-danger">Delete</button>
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
                                <td colspan="5" align="center">No branches found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $branches->links() }}
            </div>
                </div>
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
