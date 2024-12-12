<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 text-primary admin-title mb-0"><strong>Delivered</strong></h1>
                    </div>
                    <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search by customer name or order ID">

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
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            @hasrole('admin')
                            <th>Branch</th>
                            @endhasrole
                            <th>Address</th>
                            <th><center>Status</center></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>#{{ str_pad((string) $order->id, 12, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</td>
                                <td>
                                    <ul>
                                        @foreach($this->getItems($order->order_id) as $item)
                                            <li>{{ $item->delivered }}/{{ $item->to_ship + $item->out_for_delivery + $item->delivered }} {{ $item->title }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                @hasrole('admin')
                                <td>{{ $order->branch_name }}</td>
                                @endhasrole
                                <td>{{ $order->customer_address }}</td>
                                <td align="center">
                                    @if(($item->to_ship + $item->out_for_delivery + $item->delivered ) == $item->delivered)
                                        <span class="badge badge-success">Delivered</span>
                                    @else
                                        <span class="badge badge-secondary">Partially Delivered</span>
                                    @endif
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

@livewireScripts
