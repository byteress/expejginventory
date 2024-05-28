<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Request Transfer</h1>
        <div class="row">
            <div class="col-md-12">
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
                                        <th>On hand</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr wire:key="{{ $product->id }}">
                                            <td>{{ $product->code }}</td>
                                            <td>{{ $product->sku_number }}</td>
                                            <td>{{ $product->model }}</td>
                                            <td>{{ $product->description }}</td>
                                            <td><livewire:admin.stock.partial.on-hand :key="$product->id"
                                                productId="{{ $product->id }}">
                                            {{-- <td><button wire:click="select('{{ $product->id }}')" type="button"
                                                    class="btn btn-primary" data-dismiss="modal">Select</button></td> --}}
                                            <td>
                                                <form wire:submit="submit('{{ $product->id }}')">
                                                <div class="input-group mb-3">
                                                    @if($product->requested_quantity > 0)   
                                                    <input type="text" class="form-control disabled" disabled placeholder="{{ $product->requested_quantity }}">
                                                    @else
                                                    <input wire:model="quantityToRequest.{{ $product->id }}" type="number" min="1" class="form-control"
                                                        placeholder="Quantity to request"
                                                        aria-label="Quantity to request"
                                                        aria-describedby="basic-addon2">
                                                    @endif
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" @if($product->requested_quantity > 0) disabled @endif
                                                            type="submit">Request</button>
                                                    </div>
                                                </div>
                                                @error("quantityToRequest.$product->id") <span class="text-danger">{{ $message }}</span> @enderror
                                            </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No products found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $products->links() }}
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
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('alert'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('alert') }}
                            </div>
                        @endif
                        <form wire:submit="submit">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Supplier</th>
                                            <th>SKU</th>
                                            <th>Model</th>
                                            <th>Description</th>
                                            <th>On Hand</th>
                                            <th>Request</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($selectedProducts as $product)
                                            <tr wire:key="selected-{{ $product->id }}">
                                                <td>{{ $product->code }}</td>
                                                <td>{{ $product->sku_number }}</td>
                                                <td>{{ $product->model }}</td>
                                                <td>{{ $product->description }}</td>
                                                <td><span wire:init="getStocks('{{ $product->id }}')">{!! $stocks[$product->id] ?? '' !!}</span></td>
                                                <td>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon3">Qty.</span>
                                                        </div>
                                                        <input wire:model="quantities.{{ $product->id }}"
                                                            type="number" class="form-control" id="basic-url"
                                                            min="1" required aria-describedby="basic-addon3"
                                                            style = "width:50px;">
                                                    </div>
                                                </td>
                                                <td><button wire:click="remove('{{ $product->id }}')" type="button"
                                                        class="btn btn-danger" data-dismiss="modal">Remove</button></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">No products found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="col-md-4" @unlessrole('admin') style="display:none;" @endunlessrole>
                                    <div class="form-group">
                                        <label for="supplier">Branch</label>
                                        <select class="form-control" id="supplier" wire:model="branch">
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
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div> --}}
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
