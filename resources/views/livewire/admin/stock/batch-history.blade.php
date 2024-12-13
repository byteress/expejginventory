<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 text-primary admin-title mb-0"><strong>Batch Receive History</strong></h1>
                    </div>
                </div>

            </div>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3 offset-md-9">
                        <div class="form-group">
                            <input type="text" class="float-left form-control" placeholder="Search..."
                                   wire:model.live="search">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-secondary font-w">
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
