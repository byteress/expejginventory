<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <h1 class="h3 mb-2 text-primary admin-title">Order Products</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group mt-1">
                                        <input type="text" class="float-left form-control" placeholder="Search..."
                                            wire:model.live="search">
                                    </div>
                                </div>

                                <div class="col-md-3 offset-md-6">
                                    <div class="d-flex justify-content-end mt-1">
                                        <a href="#" class="btn btn-primary ">
                                            <i class="fas fa-shopping-cart"></i> View Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($products as $product)
                                    <div class="col-md-4 mb-4">
                                        <div class="card shadow">
                                            <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['title'] }}">
                                            <div class="card-body">
                                                <h5 class="card-title"><b>{{ $product['title'] }}</b></h5>
                                                <p class="card-text">{{ $product['description'] }}</p>
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="d-flex justify-content-start">
                                                            <div class="mb-0 font-weight-bold text-gray-800 price-box">
                                                                <i class="fas fa-tag"></i> $215,000
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="d-flex justify-content-end">
                                                            <a class="btn btn-primary btn-sm btn-icon-split" wire:click="addToCart({{ $product['id'] }})">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-cart-plus"></i>
                                                                </span>
                                                                <span class="text">Add to Cart</span>
                                                            </a>

                                                            {{-- <a class="btn btn-danger btn-icon-split" wire:click="addToCart({{ $product['id'] }})">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                <span class="text">Remove</span>
                                                            </a> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- / Data Table --}}
            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

    </div>
