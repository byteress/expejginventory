<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <h1 class="h3 mb-2 text-primary admin-title">Order #APR-9394399</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
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
            <div class="col-md-9">
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
                                    <input type="text" value = "300" class ="form-control mt-5 col-md-4 text-center">
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
            <div class="col-md-12">
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
                                            <div class="col-md-8">
                                                <p class="text-primary mb-0">Type</p>
                                            </div>
                                            <div class="col-md-2 text-center">
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
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                      <label class="input-group-text" for="inputGroupSelect01">Payment Type</label>
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
                                            <div class="col-md-2 offset-md-4">
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
                            <div class="col-md-2 offset-10">
                                <h4 class="text-secondary mt-1"><small>Total Payment:</small> <strong class ="text-primary">$4000</strong></h4>
                            </div>
                            <div class="col-md-2 offset-10">
                                <a href="#" class="btn btn-primary btn-lg btn-block">
                                    Place Order
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
