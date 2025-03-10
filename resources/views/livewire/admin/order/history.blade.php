<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 text-primary admin-title mb-0"><strong>Pending Orders</strong></h1>
                    </div>
                </div>

            </div>
        </div>
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
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-secondary font-w">
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Sales Representative</th>
                                @hasrole('admin')
                                    <th>Branch</th>
                                @endhasrole
                                <th>Total</th>
                                <th>Placed At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>#{{ str_pad((string) $order->id, 12, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</td>
                                    <td>{{ $order->assistant_first_name }} {{ $order->assistant_last_name }}</td>
                                    @hasrole('admin')
                                        <td>{{ $order->branch_name }}</td>
                                    @endhasrole
                                    <td>@money($order->total)</td>
                                    <td>{{ date('F j, Y', strtotime($order->placed_at)) }}</td>
                                    <td align = "center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.order.details', ['order_id' => $order->order_id]) }}" type="button"
                                                class="btn btn-primary">View</a>
                                            <button type="button"
                                                class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a data-toggle="modal" data-target="#deleteModal1" class="dropdown-item"
                                                    href="#">Cancel</a>
                                            </div>
                                            <!-- Delete Modal -->
                                            <div wire:ignore.self class="modal fade" id="deleteModal1" tabindex="-1"
                                                role="dialog" aria-labelledby="deleteModal1Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModal1Label">Delete
                                                                Product
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this product?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="button"
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
