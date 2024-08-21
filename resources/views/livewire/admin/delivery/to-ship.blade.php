<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">To Ship</h1>
        <!-- DataTales Example -->
        <form wire:submit="shipOrders">
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
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Driver</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">John Doe</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Truck</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">SLR-012</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Branch</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Branch #939393</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Notes
                                    <a href="#" class="btn btn-success btn-circle btn-sm ml-2">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Lorem Ipsum</div>
                                <div class="form-group" style = "display:none;">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                    <a href="#" class="btn btn-secondary mt-2 ml-2 float-right">
                                        Cancel
                                    </a>
                                    <a href="#" class="btn btn-success mt-2 float-right">
                                        Save
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            @hasrole('admin')
                            <th>Branch</th>
                            @endhasrole
                            <th>Address</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>#{{ str_pad((string) $order->id, 12, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</td>
                                <td>
                                    @foreach($this->getItems($order->order_id) as $item)
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-5 col-form-label">{{ $item->to_ship }} {{ $item->title }}</label>
                                            <div class="col-sm-5">
                                                <input wire:model="quantities.{{ $item->order_id }}.{{ $item->product_id }}" type="number" min="0" max="{{ $item->to_ship }}" class="form-control" id="colFormLabel" placeholder="0">
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                @hasrole('admin')
                                <td>{{ $order->branch_name }}</td>
                                @endhasrole
                                <td>{{ $order->delivery_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" align="center">No orders found</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                    @error('toShip')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Ship</button>
                    </div>
                </div>
            </div>
        </div>
        </form>

    </div>
    <!-- /.container-fluid -->

</div>
