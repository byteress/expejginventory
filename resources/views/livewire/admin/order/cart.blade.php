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
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 text-primary admin-title mb-0"><strong>Cart</strong></h1>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4 d-none d-md-block">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="mt-3 text-primary">Product</h5>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="mt-3 text-primary">Unit Price</h5>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="mt-3 text-primary">Discount</h5>
                            </div>
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary" style = "margin-left:20px;">Quantity</h5>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="mt-3 text-primary">Total Price</h5>
                            </div>
                            <div class="col-md-1 text-center">
                                <h5 class="mt-3 text-primary">Actions</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Cart Items -->
                @forelse ($items as $item)
                    <div class="card shadow mb-4" wire:key="cart-item-{{ $item->getHash() }}">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-1">
                                    {{ $this->getProduct($item->getHash())->getFirstMedia('featured')->img()->lazy()->attributes(['class' => 'img-fluid']) }}
                                </div>
                                <div class="col-md-2">
                                    <h5 class="card-title mt-5 text-primary"><strong>{{ $item->getTitle() }}</strong>
                                    </h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex justify-content-center">
                                        <div class="input-group mt-5" style = "width:150px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Php</span>
                                            </div>
                                            <input type="number" wire:model.blur="prices.{{ $item->getHash() }}"
                                                class ="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex justify-content-center">

                                        <div class="input-group mt-5" style = "width:120px;">
                                            <input type="number" wire:model.blur="discounts.{{ $item->getHash() }}"
                                                   class ="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-2">
                                    <div class="input-group mt-5">
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="decrementQuantity('{{ $item->getHash() }}')" wire:loading.attr="disabled" wire:target="decrementQuantity, incrementQuantity">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" class="text-center"
                                            wire:model="quantities.{{ $item->getHash() }}"
                                            style ="width:45px !important;" readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="incrementQuantity('{{ $item->getHash() }}')" wire:loading.attr="disabled" wire:target="decrementQuantity, incrementQuantity">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div> -->
                                <div class="col-md-2">
    <div class="input-group mt-5">
        <button class="btn btn-outline-secondary" type="button"
            wire:click="decrementQuantity('{{ $item->getHash() }}')" wire:loading.attr="disabled" wire:target="decrementQuantity, incrementQuantity">
            <i class="fas fa-minus"></i>
        </button>
        <input type="number" class="text-center"
    wire:model="quantities.{{ $item->getHash() }}"
    wire:change="updateQuantity('{{ $item->getHash() }}')"
    style="width:45px !important;"
    min="1">
        <button class="btn btn-outline-secondary" type="button"
            wire:click="incrementQuantity('{{ $item->getHash() }}')" wire:loading.attr="disabled" wire:target="decrementQuantity, incrementQuantity">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>
                                <div class="col-md-2 text-center">
                                    <h5 class="font-weight-bold mt-5">@money($item->getTotalPrice(), 'PHP', true)</h5>
                                </div>
                                <div class="col-md-1 text-center">
                                    <button class="btn btn-danger mt-5"
                                        wire:click="removeItem('{{ $item->getHash() }}')"
                                        wire:confirm="Are you sure you want to remove this item?">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p>No items found.</p>
                        </div>
                    </div>
                @endforelse

                <!-- / Cart Items -->

            </div>
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="d-flex justify-content-start">
                                    <div class="btn-group btn-group-toggle">
                                        <label class="btn btn-secondary btn-option mr-2 @if($customerType == 'new') active @endif">
                                            <input type="radio" id="option2" value="new" wire:model.live="customerType"> New
                                        </label>
                                        <label class="btn btn-secondary btn-option @if($customerType == 'existing') active @endif">
                                            <input type="radio" id="option3" value="existing" wire:model.live="customerType"> Existing
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7" style="display:  @if($customerType == 'existing') block @else none @endif;">
                                <div wire:ignore class="select-customer" id="select-customer">
                                    <!-- Select -->
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 class ="mb-2 text-primary admin-title">Customer Information</h5>
                        <hr>
                        <form class="needs-validation" novalidate>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="validationTooltip01">First name</label>
                                    <input @if($customerType == 'existing') disabled @endif type="text" class="form-control" id="validationTooltip01"
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
                                    <input @if($customerType == 'existing') disabled @endif type="text" class="form-control" id="validationTooltip02"
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
                                <div class="col-md-6 mb-3">
                                    <label for="validationTooltip03">Phone Number</label>
                                    <input @if($customerType == 'existing') disabled @endif type="text" class="form-control" id="validationTooltip03"
                                        wire:model="phone">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationTooltip03">Date of Birth</label>
                                    <input @if($customerType == 'existing') disabled @endif class = "form-control" value="{{ $dob }}" id="datepicker">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationTooltip03">Email Address</label>
                                    <input @if($customerType == 'existing') disabled @endif type="text" class="form-control" id="validationTooltip03"
                                        wire:model="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationTooltip05">Address</label>
                                    <textarea @if($customerType == 'existing') disabled @endif wire:model="address" class="form-control" id="validationTooltip05"></textarea>
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
            $('#confirmLogin').modal('show');
        });

        $wire.on('hideModal', () => {
            $('#confirmLogin').modal('hide');
        });

        let customerSearchSelect = VirtualSelect.init({
            ele: '#select-customer',
            search: true,
            multiple: false,
            placeholder: "Enter customer name",
            name: 'selected_customer',
            searchPlaceholderText: "Enter customer name",
            maxWidth: '100%',
            onServerSearch: customerSearch,
            hasOptionDescription: true
        });

        async function customerSearch(searchValue, virtualSelect){
            let customers = await $wire.searchCustomer(searchValue);
            virtualSelect.setServerOptions(customers);
        }

        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
        }).on('change', function(){
            @this.set('dob', this.value);
        });

        $wire.on('customerTypeUpdated', () => {
            customerSearchSelect.reset();
            $('#datepicker').datepicker('setDate', null);
        });

        $wire.on('dobSelected', (event) => {
            console.log(event);
            $('#datepicker').datepicker('setDate', event.dob);
        });

        document.querySelector('#select-customer').addEventListener('change', function() {
            @this.set('customerId', this.value);
        });
    </script>
@endscript
