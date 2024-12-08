<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Product List</strong></h1>
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
                <div class="col-md-3">
                    <label class="sr-only" for="inlineFormInputGroup">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Filter By Supplier</div>
                        </div>
                        <select wire:model.change="supplier" class="form-control">
                            <option value="" style="display:none">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->code }} - {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 offset-md-4">
                    <a wire:navigate href="{{ route('admin.create.product') }}"
                        class="btn btn-primary btn-icon-split float-right btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">New Product</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
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
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-secondary font-w">
                            <th>Featured Photo</th>
                            <th>SKU</th>
                            <th>Model</th>
                            <th>Description</th>
                            <th>Supplier</th>
                            <th>Regular Price</th>
                            <th>Sale Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr wire:key="{{ $product->id }}">
                                <td align="center">
                                    {{ $product->getFirstMedia('featured')->img()->lazy()->attributes(['width' => '100', 'height' => '100', 'class' => 'img-fluid']) }}
                                </td>
                                <td>
                                    <livewire:admin.product.partials.sku productId="{{ $product->id }}"
                                        sku="{{ $product->sku_number ?? null }}" :key="$product->id"/>
                                </td>
                                <td>{{ $product->model }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->supplier->name }}</td>
                                <td>@money($product->regular_price)</td>
                                <td>@money($product->sale_price)</td>

                                <td>
                                    <div class="btn-group">
                                        <a wire:navigate
                                            href="{{ route('admin.edit.product', ['product' => $product->id]) }}"
                                            type="button" class="btn btn-primary">Edit</a>
                                        <button type="button"
                                            class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a wire:navigate class="dropdown-item" href="{{ route('admin.reports.daily.items', ['product' => $product->id]) }}">Daily Report</a>ire:navigate class="dropdown-item" href="{{ route('admin.reports.monthly.items', ['product' => $product->id]) }}">Monthly Report</a>
                                            <a data-toggle="modal" data-target="#deleteModal{{ $product->id }}}}" class="dropdown-item"
                                                href="#">Delete</a>
                                        </div>
                                        <!-- Delete Modal -->
                                        <div wire:ignore.self class="modal fade" id="deleteModal{{ $product->id }}}}" tabindex="-1"
                                            role="dialog" aria-labelledby="deleteModal{{ $product->id }}}}Label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModal{{ $product->id }}}}Label">Delete Product
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
                                                        <button wire:click="delete('{{ $product->id }}')"
                                                            type="button" class="btn btn-danger">Delete</button>
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
                                <td colspan="10" align="center">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $products->links() }}
            </div>
        </div>
    </div>

</div>

@script
    <script>
        $wire.on('close-modal', () => {
            $('.modal').modal('hide');
        });
    </script>
@endscript
