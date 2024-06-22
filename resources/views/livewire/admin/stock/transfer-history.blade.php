<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Transfer History</h1>
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
                            <th>SDR #</th>
                            <th>Source</th>
                            <th>Receiving</th>
                            <th>Driver</th>
                            <th>Truck</th>
                            <th>Requested</th>
                            <th>Notes</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($transfers as $transfer)
                            <tr>
                                <td>{{ $transfer->transfer_number }}</td>
                                <td>{{ $transfer->source->name }}</td>
                                <td>{{ $transfer->receiving->name }}</td>
                                <td>{{ $transfer->truckDriver->first_name . ' '. $transfer->truckDriver->last_name }}</td>
                                <td>{{ $transfer->truck }}</td>
                                <td>{{ $transfer->requestedBy->first_name . ' '. $transfer->requestedBy->last_name }}</td>
                                <td>{{ $transfer->notes }}</td>
                                <td>{{ date('h:i a F j, Y', strtotime($transfer->created_at)) }}</td>
                                <td><a href="{{ route('admin.transfer.history.details', ['transfer' => $transfer->id]) }}"
                                       class="btn btn-primary">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No data found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $transfers->links() }}
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
