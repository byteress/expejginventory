<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">For Transfer</h1>
        <div class="row">
            <div class="col-md-6">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Requested By</th>
                                        <th>Quantity</th>
                                        <th>On Hand</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($requests as $request)
                                        @if (!in_array("{$request->product_id}.{$request->receiver}", $selected))
                                            <tr wire:key="{{ $request->product_id }}-{{ $request->receiver }}">
                                                <td>{{ $request->sku_number }} {{ $request->code }}
                                                    {{ $request->model }}
                                                    {{ $request->description }}</td>
                                                <td>{{ $request->name }}</td>
                                                <td>{{ $request->incoming }}/{{ $request->quantity }}</td>
                                                <td><livewire:admin.stock.partial.on-hand :key="$request->product_id . '-' . $request->receiver"
                                                        productId="{{ $request->product_id }}"></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button
                                                            wire:click="select('{{ $request->product_id }}.{{ $request->receiver }}')"
                                                            type="button" class="btn btn-primary">Select</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $count++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if (!$count)
                                        <tr>
                                            <td colspan="10">No requests found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Data Table --}}

            <div class="col-md-6">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <form wire:submit="transfer">
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
                                            <th>Product</th>
                                            <th>Requested By</th>
                                            <th>Quantity</th>
                                            <th>On Hand</th>
                                            <th>Transfer</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count = 0;
                                        @endphp
                                        @foreach ($requests as $request)
                                            @if (in_array("{$request->product_id}.{$request->receiver}", $selected))
                                                <tr
                                                    wire:key="selected-{{ $request->product_id }}-{{ $request->receiver }}">
                                                    <td>{{ $request->sku_number }} {{ $request->code }}
                                                        {{ $request->model }}
                                                        {{ $request->description }}</td>
                                                    <td>{{ $request->name }}</td>
                                                    <td>{{ $request->incoming }}/{{ $request->quantity }}</td>
                                                    <td><livewire:admin.stock.partial.on-hand :key="'selected-'.$request->incoming.'-' .
                                                        $request->product_id .
                                                        '-' .
                                                        $request->receiver"
                                                            productId="{{ $request->product_id }}"></td>
                                                    <td>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"
                                                                    id="basic-addon3">Qty.</span>
                                                            </div>
                                                            <input
                                                                wire:model="quantities.{{ $request->product_id }}.{{ $request->receiver }}"
                                                                type="number" class="form-control" id="basic-url"
                                                                min="1" required aria-describedby="basic-addon3"
                                                                style = "width:50px;">
                                                        </div>
                                                        @error("request.{$request->product_id}.{$request->receiver}")
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button
                                                                wire:click="remove('{{ $request->product_id }}.{{ $request->receiver }}')"
                                                                type="button" class="btn btn-danger">Remove</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                    $count++;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if (!$count)
                                            <tr>
                                                <td colspan="10">No selection.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @error('quantities')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Transfer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
