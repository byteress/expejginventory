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
                        <h1 class="h3 mb-2 text-primary admin-title">Order
                            #{{ str_pad((string) $order->id, 12, '0', STR_PAD_LEFT) }}</h1>
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
                                                <h5 class="card-title"><b>{{ $product['model'] }}</b></h5>
                                                <p class="card-text">{{ $product['description'] }}</p>
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="d-flex justify-content-start">
                                                            <div class="mb-0 font-weight-bold text-gray-800 price-box">
                                                                <i class="fas fa-tag"></i> @money($product->sale_price)
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="d-flex justify-content-end">
                                                            <a class="btn btn-primary btn-sm btn-icon-split"
                                                                wire:click="addItem('{{ $product['id'] }}')">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-cart-plus"></i>
                                                                </span>
                                                                <span class="text">Add to Order</span>
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
                                            wire:click="decrementQuantity('{{ $item->product_id }}')">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        @endif
                                        <input wire:model="quantities.{{ $item->product_id }}" type="text"
                                            class="text-center" style ="width:45px !important;" readonly>
                                        @if(!$completed)
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="incrementQuantity('{{ $item->product_id }}')">
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
                                    value="{{ $customer->email }}"">
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
                                                            <option value="Others">Others</option>
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
                                                @if (!$loop->first)
                                                    <div class="col-md-2">
                                                        <div class="d-flex justify-content-center">
                                                            <button
                                                                wire:click="removePaymentMethod({{ $loop->index }})"
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
                            </div>
                            <div class="col-md-4 offset-md-8">
                                <h4 class="text-secondary mt-1"><small>Order Total:</small><br>
                                    <strong class ="text-primary">@money($order->total)</strong></h4>


                                <h4 class="text-secondary mt-1"><small>Payment Total:</small><br>
                                    <strong class ="text-primary">@money(array_sum($amounts), 'PHP', true)</strong></h4>
                                        @error('total')<span class="text-danger">{{ $message }}</span>@enderror

                                @if ($order->requires_authorization)
                                    <button class="btn btn-danger btn-block" data-toggle="modal"
                                        data-target="#confirmLogin">
                                        Authorization Required
                                    </button>
                                @elseif(!$completed)
                                    <button wire:click="submitPayment" class="btn btn-primary btn-block">
                                        Place Order
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

<x-slot:print>
<div class="printable">
    <div class="receipt-container no-bootstrap-center">
        <div class="receipt-header">
            <p>OR# {{ $order->receipt_number }}</p>
            <p>{{ is_null($order->completed_at) ? '' : date('F j, Y', strtotime($order->completed_at)) }}</p>
        </div>
        <div class="receipt-details">
            <p>{{ $customer->first_name }} {{ $customer->last_name }}</p>
            <p>{{ $customer->phone }}</p>
            <p>{{ $customer->address }}</p>
            {{-- <p>DOB: 04/01</p> --}}
        </div>
        <div class="receipt-table-container">
            <table class="receipt-table">
                @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->title }}</td>
                    <td></td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="receipt-totals">
            <p>Total Sales: <x-money amount="{{ $order->total - ($order->total * 0.12) }}" /></p>
            <p>VAT: <x-money amount="{{ $order->total * 0.12 }}" /></p>
            <p><strong>Net Total: <x-money amount="{{ $order->total }}" /></strong></p>
        </div>
        <div class="receipt-footer">
            <p>FOR DELIVER</p>
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
        }

        .receipt-header {
            text-align: right;
        }

        .receipt-details {
            margin-top: 20px;
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
            margin-top: 100px;
            margin-left: 100px;
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

        function printPage() {
            window.print();
        }
    </script>
@endscript
