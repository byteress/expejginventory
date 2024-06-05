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
                        <div class="row mt-4 mb-4">
                            <div class="col-md-3 offset-md-7">
                                <h4 class="text-secondary mt-1"><small>Total Payment:</small> <strong class ="text-primary">$4000</strong></h4>
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
    <!-- /.container-fluid -->
</div>
