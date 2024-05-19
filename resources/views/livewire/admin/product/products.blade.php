<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Product List</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="form-group col-md-2">
                <input type="text" class="float-left form-control" placeholder="Search..." wire:model.live="search">
            </div>
            <a wire:navigate href="{{ route('admin.create.product') }}" class="btn btn-primary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">New Product</span>
            </a>
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
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Featured Photo</th>
                            <th>SKU</th>
                            <th>Model</th>
                            <th>Description</th>
                            <th>Supplier</th>
                            <th>Regular Price</th>
                            <th>Sale Price</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td align="center">{{ $product->getFirstMedia('featured')->img()->attributes(['width' => '100', 'height' => '100']) }}</td>
                            <td>{{ $product->sku_code }}-{{ $product->sku_number }}</td>
                            <td>{{ $product->model }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->supplier->name }}</td>
                            <td>{{ $product->regular_price }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td><a wire:navigate href="{{ route('admin.edit.product', ['product' => $product->id]) }}" class="btn btn-info btn-sm">Edit</a></td>
                            <td>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal1">Delete</button>
                                <!-- Delete Modal -->
                                <div wire:ignore.self class="modal fade" id="deleteModal1" tabindex="-1" role="dialog" aria-labelledby="deleteModal1Label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModal1Label">Delete Product</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this product?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button wire:click="delete('{{ $product->id }}')" type="button" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Delete Modal -->
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" align="center">No products found.</td>
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