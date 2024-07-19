<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ ucfirst($displayStatus) }} {{ ucfirst($type) }} Orders</h1>
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
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Sales Representative</th>
                                @hasrole('admin')
                                    <th>Branch</th>
                                @endhasrole
                                <th>Total</th>
                                <th>Placed At</th>
                                @if($displayStatus == 'processed')
                                    <th>Payment Type</th>
                                    <th>Payment Status</th>
                                    <th>Delivery Status</th>
                                @endif
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
                                    @if($displayStatus == 'processed')
                                        <td>{{ ucfirst($order->payment_type) }}</td>
                                        <td>{{ $this->getPaymentStatus($order->order_id) }}</td>
                                        <td>
                                            @if($order->shipping_status == 0)
                                                To Ship
                                                @elseif($order->shipping_status == 1)
                                                Out for Delivery
                                            @else
                                                Delivered
                                            @endif
                                        </td>
                                    @endif
                                    <td align = "center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.order.details', ['order_id' => $order->order_id]) }}" type="button"
                                                class="btn btn-primary">Checkout</a>
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
                    {{ $orders->links() }}
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
