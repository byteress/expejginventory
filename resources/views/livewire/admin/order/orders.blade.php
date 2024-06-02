<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <h1 class="h3 mb-2 text-primary admin-title">Orders Cart</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4 d-none d-md-block">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 class="mt-3 text-primary">Product</h5>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="mt-3 text-primary">Unit Price</h5>
                            </div>
                            <div class="col-md-1 text-center">
                                <h5 class="mt-3 text-primary">Quantity</h5>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="mt-3 text-primary">Total Price</h5>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="mt-3 text-primary">Actions</h5>
                            </div>
                        </div>
                    </div>
                </div>
                        <!-- Cart Items -->
                        @foreach ($cartItems as $item)
                        <div class="card shadow mb-4">
                            <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-1">
                                    <img src="{{ $item['image'] }}" class="img-fluid" alt="{{ $item['title'] }}">
                                </div>
                                <div class="col-md-4">
                                    <h5 class="card-title mt-5 text-primary"><strong>{{ $item['title'] }}</strong></h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex justify-content-center">
                                    <input type="text" value = "300" class ="form-control mt-5 col-md-4 text-center">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="input-group mt-5">
                                        <button class="btn btn-outline-secondary" type="button" wire:click="decrementQuantity({{ $item['id'] }})">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" class="form-control text-center" value="{{ $item['quantity'] }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" wire:click="incrementQuantity({{ $item['id'] }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h5 class="font-weight-bold mt-5">${{ number_format($item['price'], 2) }}</h5>
                                </div>
                                <div class="col-md-2 text-center">
                                        <button class="btn btn-danger mt-5" wire:click="removeItem({{ $item['id'] }})">
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </button>
                                </div>
                            </div>
                        </div>
                    </div>
                        @endforeach

                <!-- / Cart Items -->

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row mt-4 mb-4">
                            <div class="col-md-2 offset-md-8">
                                <h3 class="text-secondary mt-1">Total (2 items): <strong class ="text-primary">$4000</strong></h3>
                            </div>
                            <div class="col-md-2">
                                <a href="#" class="btn btn-primary btn-lg btn-block">
                                    Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</div>
