<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Received Product History</h1>
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
                                <th>Quantity</th>
                                <th>Received By</th>
                                <th>Received On</th>
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
                                    <td>{{ $history->quantity }}</td>
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
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
