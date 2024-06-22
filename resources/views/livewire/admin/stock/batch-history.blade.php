<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Batch Receive History</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 offset-md-9">
                        <div class="form-group">
                            <input type="text" class="float-left form-control" placeholder="Search..."
                                   wire:model.live="search">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>RSR #</th>
                            <th>Branch</th>
                            <th>Requested By</th>
                            <th>Approved By</th>
                            <th>Notes</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($batches as $batch)
                            <tr>
                                <td>{{ $batch->batch_number }}</td>
                                <td>{{ $batch->branch->name }}</td>
                                <td>{{ $batch->requested_by }}</td>
                                <td>{{ $batch->approvedBy->first_name . ' '. $batch->approvedBy->last_name }}</td>
                                <td>{{ $batch->notes }}</td>
                                <td>{{ date('h:i a F j, Y', strtotime($batch->created_at)) }}</td>
                                <td><a href="{{ route('admin.receive.history.details', ['batch' => $batch->id]) }}" class="btn btn-primary">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No data found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $batches->links() }}
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
