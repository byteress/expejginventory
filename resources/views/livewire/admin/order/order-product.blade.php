<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <h1 class="h3 mb-2 text-primary admin-title">Browse Products</h1>
            </div>
        </div>
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
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group mt-1">
                                    <input type="text" class="float-left form-control" placeholder="Search..."
                                        wire:model.live="search">
                                </div>
                            </div>

                            <div class="col-md-3" @unlessrole('admin') style="display:none;" @endunlessrole>
                                <div class="form-group mt-1">
                                    {{-- <label for="supplier">Branch</label> --}}
                                    <select @if(!empty(Cart::getItems())) disabled @endif class="form-control" id="supplier" wire:model.live="branch">
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

                            <div class="col-md-3 offset-md-3">
                                <div class="d-flex justify-content-end mt-1">
                                    <a wire:navigate href="{{ route('admin.cart') }}" class="btn btn-primary ">
                                        <i class="fas fa-shopping-cart"></i> View Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-md-4 mb-4">
                                    <div class="card shadow">
                                        {{ $product->getFirstMedia('featured')->img()->lazy()->attributes(['class' => 'card-img-top']) }}
                                        <div class="card-body">
                                            <h5 class="card-title"><b>{{ $product['model'] }}</b></h5>
                                            <p class="card-text">{{ $product['description'] }}</p>
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="d-flex justify-content-start">
                                                        <div class="mb-0 font-weight-bold text-gray-800 price-box">
                                                            <i class="fas fa-tag"></i> @money($product->regular_price)
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-7">
                                                    <div class="d-flex justify-content-end">
                                                        @if (empty(Cart::getItems(['id' => $product->id])))
                                                            <a class="btn btn-primary btn-sm btn-icon-split"
                                                                wire:click="addToCart('{{ $product['id'] }}')">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-cart-plus"></i>
                                                                </span>
                                                                <span class="text">Add to Cart</span>
                                                            </a>
                                                        @else
                                                            <a class="btn btn-danger btn-sm btn-icon-split"
                                                                wire:click="removeFromCart('{{ $product['id'] }}')">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                <span class="text">Remove</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{ $products->links() }}
                    </div>
                </div>
                {{-- / Data Table --}}
            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

</div>
