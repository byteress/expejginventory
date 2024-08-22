<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Delivery History</h1>
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
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
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
                                <td>{{ date('h:i a F j, Y', strtotime($delivery->completed_at)) }}</td>
                                <td>
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
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
