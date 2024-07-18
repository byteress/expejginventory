<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Stock History</h1>
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
                                <th>Supplier</th>
                                <th>SKU</th>
                                <th>Model</th>
                                <th>Description</th>
                                <th>Branch</th>
                                <th>Action</th>
                                <th>Quantity</th>
                                <th>Running Available</th>
                                <th>Running Reserved</th>
                                <th>Running Damaged</th>
                                <th>Running Sold</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($histories as $history)
                                <tr>
                                    <td>{{ $history->code }}</td>
                                    <td>{{ $history->sku_number }}</td>
                                    <td>{{ $history->model }}</td>
                                    <td>{{ $history->description }}</td>
                                    <td>{{ $history->name }}</td>
                                    <td>{{ $history->action }}</td>
                                    <td>{{ $history->quantity }}</td>
                                    <td>{{ $history->running_available }}</td>
                                    <td>{{ $history->running_reserved }}</td>
                                    <td>{{ $history->running_damaged }}</td>
                                    <td>{{ $history->running_sold }}</td>
                                    <td>{{ $history->first_name }} {{ $history->last_name }}</td>
                                    <td>{{ date('h:i a F j, Y', strtotime($history->date)) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $histories->links() }}
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
