<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Receive Product</h1>
        <div class="row">
            <div class="col-md-6">
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
                                        <tr>
                                            <td>{{ $product->code }}</td>
                                            <td>{{ $product->sku_number }}</td>
                                            <td>{{ $product->model }}</td>
                                            <td>{{ $product->description }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td><button wire:click="select('{{ $product->id }}')" type="button"
                                                    class="btn btn-primary" data-dismiss="modal">Select</button></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No products found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>

            </div>
            {{-- Data Table --}}

            <div class="col-md-6">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form wire:submit="submit">
                            <div class = " table-responsive">
                                <table class="table table-bordered" cellspacing="0">
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
                                            <tr wire:key="{{ $product->id }}">
                                                <td>{{ $product->code }}</td>
                                                <td>{{ $product->sku_number }}</td>
                                                <td>{{ $product->model }}</td>
                                                <td>{{ $product->description }}</td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>
                                                    <div class="input-group mb-3" style = "width:130px;">
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
                                                        class="btn btn-danger" data-dismiss="modal"><i class="fas fa-trash-alt"></i></button></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">No products found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="row" style = "margin: 0 0">
                                    <div class="col-md-4 mb-3" @unlessrole('admin') style="display:none;" @endunlessrole>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text"
                                                    for="inputGroupSelect01">Branch</label>
                                            </div>
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
                                    <div class="col-md-3 offset-md-5 mb-3">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary btn-block">Receive</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
