<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 text-primary admin-title mb-0"><strong>Stock History</strong></h1>
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
