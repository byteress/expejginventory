<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">
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
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary">Product</h5>
                            </div>
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary">Unit Price</h5>
                            </div>
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary">Discount</h5>
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
                @foreach ($items as $item)
                    <div class="card shadow mb-4" wire:key="cart-item-{{ $item->getHash() }}">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-1">
                                    {{ $this->getProduct($item->getHash())->getFirstMedia('featured')->img()->lazy()->attributes(['class' => 'img-fluid']) }}
                                </div>
                                <div class="col-md-1">
                                    <h5 class="card-title mt-5 text-primary"><strong>{{ $item->getTitle() }}</strong>
                                    </h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex justify-content-center">
                                        <input type="number" wire:model.blur="prices.{{ $item->getHash() }}"
                                            class ="form-control mt-5 col-md-4 text-center">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex justify-content-center">
                                        <input type="number" wire:model.blur="discounts.{{ $item->getHash() }}"
                                               class ="form-control mt-5 col-md-4 text-center">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group mt-5">
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="decrementQuantity('{{ $item->getHash() }}')">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" class="text-center"
                                            wire:model="quantities.{{ $item->getHash() }}"
                                            style ="width:45px !important;" readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="incrementQuantity('{{ $item->getHash() }}')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h5 class="font-weight-bold mt-5">@money($item->getTotalPrice(), 'PHP', true)</h5>
                                </div>
                                <div class="col-md-2 text-center">
                                    <button class="btn btn-danger mt-5"
                                        wire:click="removeItem('{{ $item->getHash() }}')"
                                        wire:confirm="Are you sure you want to remove this item?">
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
                                    <input type="text" class="form-control" id="validationTooltip01"
                                        wire:model="firstName">
                                    <div class="valid-tooltip">
                                        Looks good!
                                    </div>
                                    @error('firstName')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationTooltip02">Last name</label>
                                    <input type="text" class="form-control" id="validationTooltip02"
                                        wire:model="lastName">
                                    <div class="valid-tooltip">
                                        Looks good!
                                    </div>
                                    @error('lastName')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="validationTooltip03">Phone Number</label>
                                    <input type="text" class="form-control" id="validationTooltip03"
                                        wire:model="phone">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationTooltip03">Email Address</label>
                                    <input type="text" class="form-control" id="validationTooltip03"
                                        wire:model="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationTooltip05">Address</label>
                                    <textarea wire:model="address" class="form-control" id="validationTooltip05"></textarea>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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
                                <h4 class="text-secondary mt-1"><small>Total Payment:</small> <strong
                                        class ="text-primary">@money(Cart::getTotal(), 'PHP', true)</strong></h4>
                            </div>
                            <div class="col-md-2">
                                <button wire:click="placeOrder" wire:confirm="Place order now?"
                                    class="btn btn-primary btn-lg btn-block">
                                    Place Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Confirm Login Modal -->
    <div wire:ignore.self class="modal fade" id="confirmLogin" tabindex="-1" aria-labelledby="confirmLoginLabel"
        aria-hidden="true">
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
                                    <h1 class="h4 text-gray-900 mb-4">Authorization Required</h1>
                                </div>
                                @if (session('alert-authorization'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('alert-authorization') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <input wire:model="username" type="email" class="form-control"
                                        id="exampleInputEmail" aria-describedby="emailHelp"
                                        placeholder="Enter Email Address..." autocomplete="off">
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input wire:model="password" type="password" class="form-control"
                                        id="exampleInputPassword" placeholder="Password" autocomplete="off">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button wire:click="placeOrder(true)" class="btn btn-primary btn-block">
                                    Authorize
                                </button>
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

@script
    <script>
        $wire.on('authorizationRequired', () => {
            console.log('test');
            $('#confirmLogin').modal('show');
        });
    </script>
@endscript
