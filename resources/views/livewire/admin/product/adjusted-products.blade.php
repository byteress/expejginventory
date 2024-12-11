<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Adjusted Products</strong></h1>
                </div>
                <div class="col-md-2">
                    <div class="d-flex justify-content-end">
                        <a href = "#" class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print Reports</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="float-left form-control" placeholder="Search..."
                               wire:model.live="search">
                    </div>
                </div>
                <div class="col-md-4" @unlessrole('admin') style="display:none;" @endunlessrole>
                <div class="form-group">
                    <label for="supplier">Branch</label>
                    <select class="form-control" id="supplier" wire:model.live="branch">
                        <option selected value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @if (auth()->user()->branch_id == $branch->id)
                                selected @endif>
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
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Branch Name</th>
                    <th>Supplier Name</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->branch_name }}</td>
                        <td>{{ $product->supplier_name }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No products found with available quantity.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $products->links() }}

        </div>
    </div>
</div>
