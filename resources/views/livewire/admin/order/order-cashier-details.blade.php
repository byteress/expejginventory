<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 mb-2 text-primary admin-title">Order #APR-9394399</h1>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex justify-content-end">
                        <a class="btn btn-outline-secondary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1"><i class="fas fa-bars"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="collapse multi-collapse" id="multiCollapseExample1">
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
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4 d-none d-md-block">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="mt-3 text-primary">Product</h5>
                            </div>
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary">Unit Price</h5>
                            </div>
                            <div class="col-md-2">
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
                                <div class="col-md-2">
                                    <h5 class="card-title mt-5 text-primary"><strong>{{ $item['title'] }}</strong></h5>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="input-group mt-5" style = "width:150px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Php</span>
                                            </div>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group mt-5">
                                        <button class="btn btn-outline-secondary" type="button" wire:click="decrementQuantity({{ $item['id'] }})">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" class="text-center" value="{{ $item['quantity'] }}" style ="width:45px !important;"  readonly>
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

            </div>
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class ="mb-2 text-primary admin-title">Customer Information</h5>
                        <hr>
                        <form class="needs-validation" novalidate>
                            <div class="form-row">
                              <div class="col-md-6 mb-3">
                                <label for="validationTooltip01">First name</label>
                                <input type="text" class="form-control" id="validationTooltip01" value="Mark" required>
                                <div class="valid-tooltip">
                                  Looks good!
                                </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                <label for="validationTooltip02">Last name</label>
                                <input type="text" class="form-control" id="validationTooltip02" value="Otto" required>
                                <div class="valid-tooltip">
                                  Looks good!
                                </div>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="col-md-12 mb-3">
                                <label for="validationTooltip03">City</label>
                                <input type="text" class="form-control" id="validationTooltip03" required>
                                <div class="invalid-tooltip">
                                  Please provide a valid city.
                                </div>
                              </div>
                              <div class="col-md-12 mb-3">
                                <label for="validationTooltip04">State</label>
                                <select class="custom-select" id="validationTooltip04" required>
                                  <option selected disabled value="">Choose...</option>
                                  <option>...</option>
                                </select>
                                <div class="invalid-tooltip">
                                  Please select a valid state.
                                </div>
                              </div>
                              <div class="col-md-12 mb-3">
                                <label for="validationTooltip05">Zip</label>
                                <input type="text" class="form-control" id="validationTooltip05" required>
                                <div class="invalid-tooltip">
                                  Please provide a valid zip.
                                </div>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-start">
                                    <h5 class ="mb-2 text-primary admin-title">Payment Options</h5>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-end">
                                    <a href="#"
                                        class="btn btn-primary btn-icon-split float-right btn-sm">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text">New Payment Method</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="card shadow d-none d-md-block">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="text-primary mb-0">Payment Type</p>
                                            </div>
                                            <div class="col-md-2 offset-md-1 text-center">
                                                <p class="text-primary mb-0">Reference #</p>
                                            </div>
                                            <div class="col-md-2 offset-md-1 text-center">
                                                <p class="text-primary mb-0">Amount</p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <p class="text-primary mb-0">Actions</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-3">
                                <div class="card shadow mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mt-1">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                      <label class="input-group-text" for="inputGroupSelect01">Type</label>
                                                    </div>
                                                    <select class="custom-select" id="inputGroupSelect01">
                                                      <option disabled selected>Select Payment Method</option>
                                                      <option value="Cash">Cash</option>
                                                      <option value="Gcash">Gcash</option>
                                                      <option value="Paymaya">Paymaya</option>
                                                      <option value="Bank Transfer">Bank Transfer</option>
                                                      <option value="Others">Others</option>
                                                    </select>
                                                  </div>
                                            </div>
                                            <div class="col-md-4 mt-1">
                                                <div class="d-flex justify-content-center">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">#</span>
                                                    </div>
                                                    <input type="text" class="form-control">
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <div class="d-flex justify-content-center">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Php</span>
                                                    </div>
                                                    <input type="text" class="form-control">
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="d-flex justify-content-center">
                                                <button class="btn btn-danger btn-sm mt-1">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 offset-md-7">
                                <h4 class="text-secondary mt-1"><small>Total Payment:</small> <strong class ="text-primary">$4000</strong></h4>
                            </div>
                            <div class="col-md-2">
                                <a href="#" class="btn btn-primary btn-lg btn-block">
                                    Place Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


            <!-- Confirm Login Modal -->
            <div class="modal fade" id="confirmLogin" tabindex="-1" aria-labelledby="confirmLoginLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Confirm Login</h1>
                                    </div>
                                    @if (session('alert'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('alert') }}
                                        </div>
                                    @endif
                                    <form class="user" wire:submit="login">
                                        <div class="form-group">
                                            <input wire:model="email" type="email" class="form-control"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input wire:model="password" type="password"
                                                class="form-control" id="exampleInputPassword"
                                                placeholder="Password">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- End  Confirm Login Modal -->



    </div>
    <!-- /.container-fluid -->
</div>
