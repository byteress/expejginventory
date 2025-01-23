<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 text-primary admin-title mb-0"><strong>Delivery History</strong></h1>
                    </div>
                </div>

            </div>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-secondary font-w">
                            <th>Delivery #</th>
                            <th>Driver</th>
                            <th>Truck</th>
                            @hasrole('admin')
                            <th>Branch</th>
                            @endhasrole
                            <th>Date</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($deliveries as $delivery)
                            <tr>
                                <td>#{{ str_pad((string) $delivery->id, 12, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $delivery->driver_first_name }} {{ $delivery->driver_last_name }}</td>
                                <td>{{ $delivery->truck }}</td>
                                @hasrole('admin')
                                <td>{{ $delivery->branch_name }}</td>
                                @endhasrole
                                <td>{{ $delivery->own_date ? date('F j, Y', strtotime($delivery->own_date)) : date('h:i a F j, Y', strtotime($delivery->completed_at)) }}</td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.delivery.details', ['delivery_id' => $delivery->delivery_id]) }}" type="button"
                                           class="btn btn-primary">View</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" align="center">No orders found</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                    {{ $deliveries->links() }}
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
