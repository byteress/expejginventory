<div>
    <!-- Begin Page Content -->
    <div class="container-fluid no-print">
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
                        <h1 class="h3 text-primary admin-title"><strong>@if($orderType != 'regular') {{ ucfirst($orderType) }} @endif Order
                            #{{ str_pad((string) $order->id, 12, '0', STR_PAD_LEFT) }}</strong></h1>
                    </div>
                    @if(!$completed)
                    <div class="col-md-2">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-outline-secondary" data-toggle="collapse" href="#multiCollapseExample1"
                                role="button" aria-expanded="false" aria-controls="multiCollapseExample1"><i
                                    class="fas fa-bars"></i></a>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>

        <div wire:ignore.self class="collapse multi-collapse" id="multiCollapseExample1">
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

                                {{-- <div class="col-md-3 offset-md-6">
                                    <div class="d-flex justify-content-end mt-1">
                                        <a href="#" class="btn btn-primary ">
                                            <i class="fas fa-shopping-cart"></i> View Cart
                                        </a>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row">
                                @forelse ($products as $product)
                                    <div class="col-md-4 mb-4">
                                        <div class="card shadow">
                                            {{ $product->getFirstMedia('featured')->img()->lazy()->attributes(['class' => 'card-img-top']) }}
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="card-title"><b>{{ $product['model'] }}</b></h5>
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#stockModal">
                                                        In Stock <span class="badge badge-light">9</span>
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="stockModalLabel">Product Stocks</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body p-0 px-1">
                                                                <table class="table">
                                                                    <thead class="thead-light">
                                                                      <tr>
                                                                        <th scope="col">Branch</th>
                                                                        <th scope="col"><center>Stocks</center></th>
                                                                      </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                      <tr>
                                                                        <td>Laoag Branch</td>
                                                                        <td align = "center">99</td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td>Laoag Branch</td>
                                                                        <td align = "center">99</td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td>Laoag Branch</td>
                                                                        <td align = "center">99</td>
                                                                      </tr>
                                                                    </tbody>
                                                                  </table>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="card-text">{{ $product['description'] }}</p>
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="d-flex justify-content-start">
                                                            <div class="input-group mb-3 price-select">
                                                                <div class="input-group-prepend">
                                                                    <label class="input-group-text" for="product-price"><i class="fas fa-tag"></i></label>
                                                                </div>
                                                                <select wire:model="priceType.{{ $product->id }}" class="form-control price-select" id="product-price">
                                                                    <option value="regular_price" selected>@money($product->regular_price)</option>
                                                                    <option value="sale_price">@money($product->sale_price)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="d-flex justify-content-end">
                                                            @if(!$product->quantity)
                                                                <div class="btn-group">
                                                                    <button wire:click="addItem('{{ $product['id'] }}', 'purchase')" type="button" class="btn btn-secondary">Purchase Order</button>
                                                                    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <span class="sr-only">Toggle Dropdown</span>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <a wire:click="addItem('{{ $product['id'] }}', 'custom')" class="dropdown-item" href="#">Custom Order</a>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <a class="btn btn-primary btn-sm btn-icon-split"
                                                                   wire:click="addItem('{{ $product['id'] }}')">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-cart-plus"></i>
                                                                </span>
                                                                    <span class="text">Add to Cart</span>
                                                                </a>
                                                            @endif

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
                                @empty
                                    <div class="col-md-4 mb-4">
                                        No products found
                                    </div>
                                @endforelse
                            </div>
                            {{ $products->links() }}
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
                            <div class="col-md-3">
                                <h5 class="mt-3 text-primary">Product</h5>
                            </div>
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary text-center">Unit Price</h5>
                            </div>
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary text-center">Discount</h5>
                            </div>
                            <div class="col-md-2">
                                <h5 class="mt-3 text-primary" style = "margin-left:20px;">Quantity</h5>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="mt-3 text-primary">Total Price</h5>
                            </div>
                            @if(!$completed)
                            <div class="col-md-1 text-center">
                                <h5 class="mt-3 text-primary">Actions</h5>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Cart Items -->
                @foreach ($cartItems as $item)
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-1">
                                    {{ $this->getProduct($item->product_id)->getFirstMedia('featured')->img()->lazy()->attributes(['class' => 'img-fluid']) }}
                                </div>
                                <div class="col-md-2">
                                    <h5 class="card-title mt-5 text-primary"><strong>{{ $item->title }}</strong></h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex justify-content-center">
                                        <div class="input-group mt-5" style = "width:150px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Php</span>
                                            </div>
                                            <input wire:model.blur="prices.{{ $item->product_id }}" type="number" @if($completed) disabled @endif
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex justify-content-center">
                                        <div class="input-group mt-5" style = "width:150px;">
                                            <input wire:model.blur="discounts.{{ $item->product_id }}" type="number" @if($completed) disabled @endif
                                            class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group mt-5">
                                        @if(!$completed)
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="decrementQuantity('{{ $item->product_id }}')" wire:loading.attr="disabled" wire:target="decrementQuantity, incrementQuantity">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        @endif
                                        <input wire:model="quantities.{{ $item->product_id }}" type="text"
                                            class="text-center" style ="width:45px !important;" readonly>
                                        @if(!$completed)
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="incrementQuantity('{{ $item->product_id }}')" wire:loading.attr="disabled" wire:target="decrementQuantity, incrementQuantity">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h5 class="font-weight-bold mt-5">@money($item->price * $item->quantity)</h5>
                                </div>
                                <div class="col-md-1 text-center">
                                    @if(!$completed)
                                    <button class="btn btn-danger mt-5"
                                        wire:click="removeItem('{{ $item->product_id }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
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
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip01">First name</label>
                                <input disabled type="text" class="form-control" id="validationTooltip01"
                                    value="{{ $customer->first_name }}" required>
                                <div class="valid-tooltip">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip02">Last name</label>
                                <input disabled type="text" class="form-control" id="validationTooltip02"
                                    value="{{ $customer->last_name }}" required>
                                <div class="valid-tooltip">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip03">Phone Number</label>
                                <input disabled type="text" class="form-control" id="validationTooltip03"
                                    value="{{ $customer->phone }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip03">Email Address</label>
                                <input disabled type="text" class="form-control" id="validationTooltip03"
                                    value="{{ $customer->email }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip05">Address</label>
                                <textarea disabled class="form-control" id="validationTooltip05">{{ $customer->address }}</textarea>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ openAddress: $wire.entangle('sameAddress'), openDeliver: $wire.entangle('deliveryType'), openDeliver: $wire.entangle('deliveryType'), deliveryFee: $wire.entangle('deliveryFee').live, paymentType: $wire.entangle('paymentType') }" class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class ="mb-2 text-primary admin-title">Delivery Information</h5>
                        <hr>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                    <div class="btn-group btn-group-toggle">
                                            <label class="btn btn-secondary btn-option mr-2" :class="openDeliver == 'pickup' && 'active'">
                                                <input x-on:click="openDeliver = 'pickup', deliveryFee = 0" wire:model="deliveryType" type="radio" id="option2" value="pickup" @if($completed) disabled @endif> Pickup
                                            </label>
                                            <label for="delivery-type-deliver" class="btn btn-secondary btn-option mr-2" :class="openDeliver == 'deliver' && 'active'">
                                                <input x-on:click="openDeliver = 'deliver'" wire:model="deliveryType" type="radio" id="delivery-type-deliver" value="deliver"  @if($completed) disabled @endif> Deliver
                                            </label>
                                            <label for="delivery-type-previous" class="btn btn-secondary btn-option mr-2" :class="openDeliver == 'previous' && 'active'">
                                                <input x-on:click="openDeliver = 'previous'" wire:model="deliveryType" type="radio" id="delivery-type-previous" value="previous"  @if($completed) disabled @endif> Previous Order
                                            </label>
                                    </div>
                            </div>
                        </div>
                        <div x-show="openDeliver == 'deliver'" class="form-row">

                            <div class="col-md-12 mb-3">
                                <label for="delivery-fee">Delivery Fee</label>
                                <input type="number" min="0" class="form-control" id="delivery-fee" wire:model.live.blur="deliveryFee" @if($completed) disabled @endif>
                                @error('deliveryFee')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input x-on:click="openAddress = ! openAddress, $wire.set('deliveryAddress', '')" class="form-check-input" type="checkbox" id="flexCheckDefault" wire:model="sameAddress" @if($completed) disabled @endif>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Same as customer address
                                    </label>
                                </div>
                            </div>

                            <div x-show="!openAddress" class="col-md-12 mb-3">
                                <label for="delivery-address">Address</label>
                                <textarea wire:model="deliveryAddress" class="form-control" id="delivery-address" @if($completed) disabled @endif></textarea>
                                @error('deliveryAddress')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div x-show="openDeliver == 'previous' && paymentType == 'installment'" class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip03">Installment Start Date</label>
                                <input wire:model="installmentStartDate" class = "form-control" id="datepicker" autocomplete="false">
                                @error('installmentStartDate')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class ="mt-2 text-primary admin-title">Payment Options</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            <div class="btn-group btn-group-toggle">
                                                <label class="btn btn-secondary btn-option mr-2 @if($paymentType == 'full') active @endif">
                                                  <input wire:model.live="paymentType" type="radio" id="option2" value="full"  @if($completed) disabled @endif> Pay Now
                                                </label>
                                                <label class="btn btn-secondary btn-option mr-2 @if($paymentType == 'installment') active @endif">
                                                  <input wire:model.live="paymentType" type="radio" id="option3" value="installment"  @if($completed) disabled @endif> Installment
                                                </label>
{{--                                                <label class="btn btn-secondary btn-option @if($paymentType == 'cod') active @endif">--}}
{{--                                                    <input type="radio" id="option4" value="cod" wire:model.live="paymentType" @if($completed) disabled @endif> COD--}}
{{--                                                </label>--}}
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                @if($cancelled)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Previous Receipt Number</span>
                                                </div>
                                                <input type="text" class="form-control" disabled placeholder="Previous Receipt Number" value="{{ $cancelled->receipt_number }}">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Receipt Number</span>
                                                </div>
                                                <input type="text" class="form-control" wire:model="receiptNumber" placeholder="Receipt Number" @if($completed) disabled @endif>
                                            </div>
                                            @error('receiptNumber')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if(!$completed)
                                        <div class="d-flex justify-content-end mt-1">
                                            <button wire:click="newPaymentMethod"
                                                class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-plus"></i>
                                                </span>
                                                <span class="text">New Payment Method</span>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            <div class="col-md-12 mt-2">
                                <div class="card shadow d-none d-md-block">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="text-primary mb-0">Payment Type</p>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <p class="text-primary mb-0">Reference #</p>
                                            </div>
                                            <div class="col-md-3 text-center">
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
                                        @foreach ($amounts as $amount)
                                            <div class="row" wire:key="payment-methods-{{ $loop->index }}">
                                                <div class="col-md-4 mt-1">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text"
                                                                for="inputGroupSelect01">Type</label>
                                                        </div>
                                                        <select class="custom-select" id="inputGroupSelect01" @if($completed) disabled @endif
                                                            wire:model="paymentMethods.{{ $loop->index }}">
                                                            <option disabled selected>Select Payment Method</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Gcash">Gcash</option>
                                                            <option value="Paymaya">Paymaya</option>
                                                            <option value="Bank Transfer">Bank Transfer</option>
                                                            <option value="COD">COD</option>
                                                            <option value="Check">Check</option>
                                                            <option value="Card">Card</option>
                                                            <option value="Financing">Financing</option>
                                                        </select>
                                                    </div>
                                                    @error('paymentMethods.' . $loop->index)
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">#</span>
                                                            </div>
                                                            <input type="text" class="form-control" @if($completed) disabled @endif
                                                                wire:model="referenceNumbers.{{ $loop->index }}">
                                                        </div>
                                                    </div>
                                                    @error('referenceNumbers.' . $loop->index)
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Php</span>
                                                            </div>
                                                            <input type="number" class="form-control" @if($completed) disabled @endif
                                                                wire:model.live="amounts.{{ $loop->index }}">
                                                        </div>
                                                    </div>
                                                    @error('amounts.' . $loop->index)
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                    <div class="col-md-2">
                                                        <div class="d-flex justify-content-center">
                                                            <div class="form-check form-switch">
                                                                <input wire:model="credit.{{ $loop->index }}" class="form-check-input" type="checkbox" id="flexSwitchCheckChecked{{ $loop->index }}">
                                                                <label class="form-check-label" for="flexSwitchCheckChecked{{ $loop->index }}">Credit</label>
                                                            </div>
                                                            @if (!$loop->first)
                                                            <div class="form-check form-switch">
                                                            <button
                                                                wire:click="removePaymentMethod({{ $loop->index }})"
                                                                class="btn btn-danger btn-sm mt-1">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if($paymentType == 'cod' && $completed)

                            <div class="row">
                                <hr>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <h3>Full Payment</h3>
                                        <div class="d-flex">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Receipt Number</span>
                                                </div>
                                                <input type="text" class="form-control" wire:model="receiptNumberCod" placeholder="Receipt Number" @if($completedCod) disabled @endif>
                                            </div>
                                            @error('receiptNumberCod')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if(!$completedCod)
                                            <div class="d-flex justify-content-end mt-1">
                                                <button wire:click="newPaymentMethodCod"
                                                        class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-plus"></i>
                                                </span>
                                                    <span class="text">New Payment Method</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="card shadow d-none d-md-block">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="text-primary mb-0">Payment Type</p>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <p class="text-primary mb-0">Reference #</p>
                                            </div>
                                            <div class="col-md-3 text-center">
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
                                        @foreach ($amountsCod as $amount)
                                            <div class="row" wire:key="payment-methods-{{ $loop->index }}">
                                                <div class="col-md-4 mt-1">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text"
                                                                   for="inputGroupSelect01">Type</label>
                                                        </div>
                                                        <select class="custom-select" id="inputGroupSelect01" @if($completedCod) disabled @endif
                                                        wire:model="paymentMethodsCod.{{ $loop->index }}">
                                                            <option disabled selected>Select Payment Method</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Gcash">Gcash</option>
                                                            <option value="Paymaya">Paymaya</option>
                                                            <option value="Bank Transfer">Bank Transfer</option>
                                                            <option value="COD">COD</option>
                                                            <option value="Check">Check</option>
                                                            <option value="Card">Card</option>
                                                            <option value="Financing">Financing</option>
                                                        </select>
                                                    </div>
                                                    @error('paymentMethodsCod.' . $loop->index)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">#</span>
                                                            </div>
                                                            <input type="text" class="form-control" @if($completedCod) disabled @endif
                                                            wire:model="referenceNumbersCod.{{ $loop->index }}">
                                                        </div>
                                                    </div>
                                                    @error('referenceNumbersCod.' . $loop->index)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Php</span>
                                                            </div>
                                                            <input type="number" class="form-control" @if($completedCod) disabled @endif
                                                            wire:model.live="amountsCod.{{ $loop->index }}">
                                                        </div>
                                                    </div>
                                                    @error('amountsCod.' . $loop->index)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                @if (!$loop->first)
                                                    <div class="col-md-2">
                                                        <div class="d-flex justify-content-center">
                                                            <button
                                                                wire:click="removePaymentMethodCod({{ $loop->index }})"
                                                                class="btn btn-danger btn-sm mt-1">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if(!$completedCod)
                                <div class="col-md-4 fa-pull-right">
                                    <button wire:click="submitCodPayment" class="btn btn-primary btn-block">
                                        Complete COD Payment
                                    </button>
                                </div>
                                @endif
                            </div>
                        @endif
                        <div class="row">
                            @if($paymentType == 'installment')
                                <div class="col-md-8">
                                    <div class="card shadow mb-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class ="text-primary admin-title mt-2">Installment</h5>
                                                </div>
                                                <div class="col-md-6">
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                                            <thead>
                                                            <tr>
                                                                <th>Option</th>
                                                                <th>Interest(%)</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text"
                                                                                   for="inputGroupSelect01">Option</label>
                                                                        </div>
                                                                        <select wire:model="months" class="custom-select" id="inputGroupSelect01" @if($completed) disabled @endif>
                                                                            <option disabled selected>Select Installment Option</option>
                                                                            <option value="1">1 month</option>
                                                                            <option value="2">2 months</option>
                                                                            <option value="3">3 months</option>
                                                                            <option value="4">4 months</option>
                                                                            <option value="5">5 months</option>
                                                                            <option value="6">6 months</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                        <input wire:model="rate" type="number" min="0" class="form-control" required @if($completed) disabled @endif>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4 fa-pull-right">
                                <h4 class="text-secondary mt-1"><small>Order Total:</small><br>
                                    <strong class ="text-primary">@money($order->total + $deliveryFee * 100)</strong></h4>


                                <h4 class="text-secondary mt-1"><small>@if($paymentType == 'full')Payment Total:@else Down Payment: @endif</small><br>
                                    <strong class ="text-primary">@money(array_sum($amounts), 'PHP', true)</strong></h4>
                                @error('total')<span class="text-danger">{{ $message }}</span>@enderror

                                @if($paymentType == 'cod' && $completed)
                                    <h4 class="text-secondary mt-1"><small>Full Payment</small><br>
                                        <strong class ="text-primary">@money(array_sum($amountsCod), 'PHP', true)</strong></h4>
                                    @error('totalCod')<span class="text-danger">{{ $message }}</span>@enderror
                                @endif

                                @if ($order->requires_authorization)
                                    <button class="btn btn-danger btn-block" data-toggle="modal"
                                            data-target="#confirmLogin">
                                        Authorization Required
                                    </button>
                                @elseif(!$completed)
                                    <button wire:click="submitPayment" wire:loading.attr="disabled" wire:target="submitPayment" class="btn btn-primary btn-block">
                                        Process Order
                                    </button>
                                @else
                                    <button onclick="window.print()" class="btn btn-secondary btn-block" onclick="printPage()">
                                        Print
                                    </button>
                                @endif
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Confirm Login Modal -->
        <div wire:ignore.self class="modal fade" id="confirmLogin" tabindex="-1"
            aria-labelledby="confirmLoginLabel" aria-hidden="true">
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
                                        <h1 class="h4 text-gray-900 mb-4">Confirm Order</h1>
                                    </div>
                                    @if (session('alert-auth'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('alert-auth') }}
                                        </div>
                                    @endif
                                    <form class="user" wire:submit="confirmOrder">
                                        <div class="form-group">
                                            <input wire:model="email" type="email" class="form-control"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input wire:model="password" type="password" class="form-control"
                                                id="exampleInputPassword" placeholder="Password">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Confirm
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
</div>

<x-slot:print>
<div class="printable">
    <div class="receipt-container no-bootstrap-center">
        <div class="receipt-header">
            <p>TR# {{ ($transaction) ? str_pad((string) $transaction->transaction_number, 5, '0', STR_PAD_LEFT) : '' }} {{ $this->getReceiptType() }}# {{ $order->receipt_number }}</p>
            <p>{{ is_null($order->completed_at) ? '' : date('F j, Y', strtotime($order->completed_at)) }}</p>
        </div>
        <div class="receipt-details">
            <p>{{ $customer->first_name }} {{ $customer->last_name }}</p>
            <p>{{ $customer->phone }}</p>
            <p style = "margin-top:20px;">{{ $customer->address }}</p>
            {{-- <p>DOB: 04/01</p> --}}
        </div>
        <div class="receipt-table-container">
            <table class="receipt-table" style = "margin-top:60px;">
                @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $this->getProductSupplierCode($item->product_id) }} {{ $item->title }}</td>
                    <td style="text-align: right;"><x-money amount="{{ $item->price * $item->quantity }}"></x-money></td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="receipt-totals">
            @php
                $orderTotal = $order->total + $order->delivery_fee;
                $paymentTotal = $this->getPaymentTotalWithoutCod();
            @endphp
            <table style = "width:100%;">
                @if($order->delivery_fee > 0)
                <tr>
                    <td>Delivery Fee: </td>
                    <td><x-money amount="{{ $order->delivery_fee }}" /></td>
                </tr>
                @endif
                <tr>
                    <td>Total Sales: </td>
                    <td><x-money amount="{{ $orderTotal - ($orderTotal * 0.12) }}" /></td>
                </tr>
                <tr>
                    <td>VAT: </td>
                    <td><x-money amount="{{ $orderTotal * 0.12 }}" /></td>
                </tr>
                <tr>
                    <td>Net Total: </td>
                    <td><x-money amount="{{ $orderTotal }}" /></td>
                </tr>
                    @if($paymentTotal != $orderTotal)
                <tr>
                    <td>Downpayment: </td>
                    <td><x-money amount="{{ $paymentTotal }}" /></td>
                </tr>

                <tr>
                    <td>Balance: </td>
                    <td><x-money amount="{{ $orderTotal - $paymentTotal}}" /></td>
                </tr>
                @endif
            </table>
        </div>
        <div class="receipt-footer">
            <p>{{ $this->getPaymentBreakdown() }}</p>
            <p>FOR {{ strtoupper($order->delivery_type) }}</p>
            <p>Assisted by: {{ $assistant->first_name }}</p>
            <p>Cashier: {{ is_null($cashier) ? '' : $cashier->first_name }}</p>
        </div>
    </div>
</div>
</x-slot>

@assets
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .receipt-container {
            width: 600px;
            margin: 0;
            padding: 0;
            float: left;
            margin-top:102px;
        }

        .receipt-header {
            text-align: right;
        }

        .receipt-details {
            margin-top: 20px;
            margin-left: 40px;
        }

        .receipt-details p {
            margin: 2px 0;
        }

        .receipt-table-container {
            margin-top: 20px;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-table th,
        .receipt-table td {
            border: none;
            padding: 8px;
            text-align: left;
        }

        .receipt-totals {
            margin-top: 20px;
            float: right;
        }

        .receipt-totals p {
            margin: 2px 0;
        }

        .receipt-footer {
            margin-top: 80px;
            margin-left: 50px;
            max-width:250px;
            word-wrap:break-word;
        }

        .receipt-footer p {
            margin: 2px 0;
        }
        .no-print {
            display: block;
        }
        .printable {
            display: none;
        }
        @media print {
            .no-print {
                display: none;
            }
            .printable {
                display: block;
            }
        }
    </style>
@endassets

@script
    <script>
        $wire.on('order-confirmed', () => {
            $('#confirmLogin').modal('hide');
        });

        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
        }).on('change', function(){
            @this.set('installmentStartDate', this.value);
        });

        function printPage() {
            window.print();
        }
    </script>
@endscript
