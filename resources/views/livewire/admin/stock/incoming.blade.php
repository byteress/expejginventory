<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Incoming</h1>
        <div class="row">
            <div class="col-md-12">
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
                                    @forelse ($requests as $request)
                                        <tr wire:key="{{ $request->product_id }}-{{ $request->receiver }}">
                                            <td>{{ $request->sku_number }} {{ $request->code }}
                                                {{ $request->model }}
                                                {{ $request->description }}</td>
                                            <td>{{ $request->name }}</td>
                                            <td>{{ $request->incoming }}/{{ $request->quantity }}</td>
                                            <td><livewire:admin.stock.partial.on-hand :key="$request->product_id .
                                                '-' .
                                                $request->receiver .
                                                '-' .
                                                $request->incoming"
                                                    productId="{{ $request->product_id }}"></td>
                                            <td>
                                                <form wire:submit="submit('{{ $request->product_id }}')">
                                                    <div class="input-group mb-3">
                                                        <input
                                                            wire:model="quantityToReceive.{{ $request->product_id }}"
                                                            type="number" min="1" class="form-control"
                                                            placeholder="Quantity to receive"
                                                            aria-label="Quantity to receive"
                                                            aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary"
                                                                type="submit">Receive</button>
                                                        </div>
                                                    </div>
                                                    @error("quantityToReceive.$request->id")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
                    </div>
                </div>

            </div>
            {{-- Data Table --}}

            {{-- <div class="col-md-6">
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
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>Supplier</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>Supplier</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>61</td>
                                        <td>2011/04/25</td>
                                        <td>Recieved</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> --}}
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
