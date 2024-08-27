<div x-data="{ edit: false }">
    <!-- Begin Page Content -->
    <div class="container-fluid">
        @php
            switch ($delivery->status) {
                case 0:
                    $class = 'primary';
                    break;

                    case 1:
                    $class = 'secondary';
                    break;

                    case 2:
                    $class = 'success';
                    break;

                    case 3:
                    $class = 'danger';
                    break;
                default:
                    $class = 'primary';
            }
        @endphp
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Delivery #{{ str_pad((string) $delivery->id, 12, '0', STR_PAD_LEFT) }} <span class="badge badge-{{ $class }}">{{ $this->getStatus($delivery->status) }}</span></h1>
        <!-- DataTales Example -->
        <form wire:submit="setAsDelivered">
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $delivery->driver_first_name }} {{ $delivery->driver_last_name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Truck</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $delivery->truck }}</div>
                                </div>
                            </div>
                        </div>
                        @hasrole('admin')
                        <div class="col-md-4">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Branch</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $delivery->branch_name }}</div>
                                </div>
                            </div>
                        </div>
                        @endhasrole
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Notes
                                        <a x-show="!edit" x-on:click="edit = true" href="#" class="btn btn-success btn-circle btn-sm ml-2">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    </div>
                                    <div x-show="!edit" class="h5 mb-0 font-weight-bold text-gray-800">{{ $notes }}</div>
                                    <div x-show="edit" class="form-group" style = "display:none;">
                                        <textarea wire:model="notes" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <a x-on:click="edit = false" href="#" class="btn btn-secondary mt-2 ml-2 float-right">
                                            Cancel
                                        </a>
                                        <a wire:click="updateNotes" x-on:click="edit = false" href="#" class="btn btn-success mt-2 float-right">
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
                                <th>Address</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($orders as $order)
                                <tr wire:key="{{ $order->id }}">
                                    <td>#{{ str_pad((string) $order->id, 12, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</td>
                                    <td>
                                        @foreach($this->getItems($order->order_id) as $item)
                                            <div wire:key="{{ $item->order_id }}.{{ $item->product_id }}" class="form-group row">
                                                <label for="colFormLabel" class="col-sm-5 col-form-label">{{ $item->delivered }}/{{ $item->quantity }} {{ $this->getProductTitle($order->order_id, $item->product_id) }}</label>
                                                @if($delivery->status == 0)
                                                <div class="col-sm-5">
                                                    <input wire:model="quantities.{{ $order->order_id }}.{{ $item->product_id }}" type="number" min="0" max="{{ $item->quantity }}" class="form-control" id="colFormLabel" placeholder="Enter quantity">
                                                    @error("quantities.$order->order_id.$item->product_id")
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
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

                        @if($delivery->status == 0)
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Set as Delivered</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>

    </div>
    <!-- /.container-fluid -->

</div>
