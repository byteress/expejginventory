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
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="supplier">Driver</label>
                            <select class="form-control" id="driver" wire:model="driver">
                                <option selected value="">Select Driver</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->first_name }}
                                        {{ $driver->last_name }}</option>
                                @endforeach
                            </select>
                            @error('driver')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="regular-price">Truck</label>
                            <input type="text" class="form-control" id="regular-price"
                                   wire:model="truck" placeholder="">
                            @error('truck')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="regular-price">Notes</label>
                            <input type="text" class="form-control" id="regular-price"
                                   wire:model="notes" placeholder="">
                            @error('notes')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4" @unlessrole('admin') style="display:none;" @endunlessrole>
                        <div class="form-group">
                            <label for="supplier">Branch</label>
                            <select class="form-control" id="supplier" wire:model.live="branch">
                                <option selected value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                            @if (auth()->user()->branch_id == $branch->id) selected @endif>
                                        {{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
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
