<div>
    <div class="container-fluid no-print">
    
        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 mb-2 text-primary admin-title"><strong>Daily Reports</strong></h1>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex justify-content-end">
                            <a href = "#" class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print Reports</a>
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
    
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Date</div>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button wire:click="changeDate('decrement')" type="button" class="btn btn-primary">
                                <i class="fas fa-chevron-left"></i></button>
    
                            <input class = "form-control" value="{{ $date ?? date('Y-m-d') }}" id="datepicker" style ="border-radius: 0;">
    
                            <button wire:click="changeDate('increment')" type="button" class="btn btn-primary">
                                <i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4" @unlessrole('admin') style="display:none;" @endunlessrole>
                        <div class="form-group">
                            <label for="supplier">Branch</label>
                            <select class="form-control" id="supplier" wire:model.live="branch">
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
                </div>
    
            </div>
        </div>
    
    
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="true">Sales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pcv-tab" data-toggle="tab" href="#pcv" role="tab" aria-controls="pcv" aria-selected="false">PCVs</a>
            </li>
        </ul>
    
        <div class="tab-content" id="reportTabsContent">
            <!-- Sales Tab -->
            <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h3>Sales</h3>
                        <!-- Sales table content -->
                        <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="font-w">
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">ID</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">SI</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">DR</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">CI</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">CHG</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">CR</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Representative</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Customer</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Unit</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Particulars</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Gross Price</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Total Amount</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Discount</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">COD</th>
                                    <th colspan="5" class="text-center" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Mode of Payment</th>
                                    <th rowspan="2" style="position: sticky; top: 0; z-index: 10; background-color: #6c757d;">Financing</th>
                                </tr>
                                <tr class="font-w">
                                    <th style="position: sticky; top: 40px; z-index: 10; background-color: #6c757d;">Check</th>
                                    <th style="position: sticky; top: 40px; z-index: 10; background-color: #6c757d;">Bank</th>
                                    <th style="position: sticky; top: 40px; z-index: 10; background-color: #6c757d;">Card</th>
                                    <th style="position: sticky; top: 40px; z-index: 10; background-color: #6c757d;">Cash</th>
                                    <th style="position: sticky; top: 40px; z-index: 10; background-color: #6c757d;">Gcash</th>
                                </tr>
                            </thead>
                            <tbody style="color: #333333; !important;">
                            @php
                            $totalGrossPrice = 0;
                            $totalAmount = 0;
                            $totalDiscount = 0;
                            $totalCod = 0;
                            @endphp
                            @forelse($transactions as $transaction)
                                @php
                                $items = $this->getItems($transaction->order_id);
                                $rowspan = count($items);
    
                                if($transaction->delivery_fee > 0) $rowspan = $rowspan + 1;
                                $receiptType = $this->getReceiptType($transaction);
    
                                @endphp
                                    @foreach($items as $item)
                                    @if($loop->index == 0)
                                        @php
                                            $discount = $this->getDiscount($transaction->order_id);
    
                                            if($transaction->status == 1 || (!$this->isSameDayCancelled($transaction->order_id) && !$this->isSameDayRefunded($transaction->order_id))){
                                                $totalGrossPrice = $totalGrossPrice + $item->original_price * $item->quantity;
                                                $totalAmount += $item->price * $item->quantity;
                                                $totalDiscount += $item->original_price * $item->quantity - $item->price * $item->quantity;
                                                $totalCod = $totalCod + $this->getPaymentAmount($transaction->transaction_id, 'COD');
                                            }
                                        @endphp
                                    <tr>
                                        <td rowspan="{{ $rowspan }}">{{ $transaction->order_number  }}</td>
    
                                        <td rowspan="{{ $rowspan }}"><a href="{{ route('admin.order.details', ['order_id' => $transaction->order_id]) }}">{{ $receiptType == 'SI' ?  $transaction->receipt_number : '' }}</a></td>
                                        <td rowspan="{{ $rowspan }}"><a href="{{ route('admin.order.details', ['order_id' => $transaction->order_id]) }}">{{ $receiptType == 'DR' ?  $transaction->receipt_number : '' }}</a></td>
                                        <td rowspan="{{ $rowspan }}"><a href="{{ route('admin.order.details', ['order_id' => $transaction->order_id]) }}">{{ $receiptType == 'CI' ?  $transaction->receipt_number : '' }}</a></td>
                                        <td rowspan="{{ $rowspan }}"><a href="{{ route('admin.order.details', ['order_id' => $transaction->order_id]) }}">{{ $receiptType == 'CHG'?  $transaction->receipt_number : '' }}</a></td>
                                        <td rowspan="{{ $rowspan }}"><a href="{{ route('admin.order.details', ['order_id' => $transaction->order_id]) }}">{{ $receiptType == 'CR' ?  $transaction->receipt_number : '' }}</a></td>
                                        <td rowspan="{{ $rowspan }}">{{ $transaction->fname }} {{ $transaction->lname }}</td>
                                        <td rowspan="{{ $rowspan }}">{{ $transaction->first_name }} {{ $transaction->last_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><a href="{{ route('admin.reports.monthly.items', ['product' => $item->product_id]) }}">{{ $this->getProductSupplierCode($item->product_id) }} {{ $item->title }}</a></td>
                                        <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->original_price * $item->quantity) @endif</td>
                                        <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->price * $item->quantity) @endif</td>
                                        <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->original_price * $item->quantity - $item->price * $item->quantity) @endif</td>
                                        <td rowspan="{{ $rowspan }}">@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($this->getPaymentAmount($transaction->transaction_id, 'COD'))@endif</td>
                                        <td rowspan="{{ $rowspan }}">
                                            @if($transaction->cancelled_order_id)
                                                @php
                                                    $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Check');
                                                @endphp
                                                 @if($cancelledInfo['creditedAmount'] > 0)
                                                    <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                                    <a wire:navigate href="{{ route('admin.order.details', ['order_id' => $transaction->cancelled_order_id]) }}">{{ $cancelledInfo['receipt'] }}</a>
                                                @endif
                                                @if($cancelledInfo['amount'] > 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                                @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                            @else
                                                <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Check'))</p>
                                            @endif
                                        </td>
                                        <td rowspan="{{ $rowspan }}">
                                            @if($transaction->cancelled_order_id)
                                                @php
                                                    $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Bank Transfer');
                                                @endphp
    
                                                @if($cancelledInfo['creditedAmount'] > 0)
                                                    <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                                    <a wire:navigate href="{{ route('admin.order.details', ['order_id' => $transaction->cancelled_order_id]) }}">{{ $cancelledInfo['receipt'] }}</a>
                                                @endif
                                                @if($cancelledInfo['amount'] > 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                                @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                            @else
                                                <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Bank Transfer'))</p>
                                            @endif
                                        </td>
                                        <td rowspan="{{ $rowspan }}">
                                            @if($transaction->cancelled_order_id)
                                                @php
                                                    $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Card');
                                                @endphp
                                                 @if($cancelledInfo['creditedAmount'] > 0)
                                                    <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                                    <a wire:navigate href="{{ route('admin.order.details', ['order_id' => $transaction->cancelled_order_id]) }}">{{ $cancelledInfo['receipt'] }}</a>
                                                @endif
                                                @if($cancelledInfo['amount'] > 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                                @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                            @else
                                                <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Card'))</p>
                                            @endif
                                        </td>
                                        <td rowspan="{{ $rowspan }}">@if($transaction->cancelled_order_id)
                                                @php
                                                    $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Cash');
                                                @endphp
                                                 @if($cancelledInfo['creditedAmount'] > 0)
                                                    <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                                    <a wire:navigate href="{{ route('admin.order.details', ['order_id' => $transaction->cancelled_order_id]) }}">{{ $cancelledInfo['receipt'] }}</a>
                                                @endif
                                                @if($cancelledInfo['amount'] > 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                                @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                            @else
                                                <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Cash'))</p>
                                            @endif</td>
                                        <td rowspan="{{ $rowspan }}">@if($transaction->cancelled_order_id)
                                                @php
                                                    $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Gcash');
                                                @endphp
                                                 @if($cancelledInfo['creditedAmount'] > 0)
                                                    <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                                    <a wire:navigate href="{{ route('admin.order.details', ['order_id' => $transaction->cancelled_order_id]) }}">{{ $cancelledInfo['receipt'] }}</a>
                                                @endif
                                                @if($cancelledInfo['amount'] > 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                                @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                            @else
                                                <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Gcash'))</p>
                                            @endif</td>
                                        <td rowspan="{{ $rowspan }}">@if($transaction->cancelled_order_id)
                                                @php
                                                    $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Financing');
                                                @endphp
                                                 @if($cancelledInfo['creditedAmount'] > 0)
                                                    <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                                    <a wire:navigate href="{{ route('admin.order.details', ['order_id' => $transaction->cancelled_order_id]) }}">{{ $cancelledInfo['receipt'] }}</a>
                                                @endif
                                                @if($cancelledInfo['amount'] > 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                                @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                                    @money($cancelledInfo['amount'])
                                                @endif
                                            @else
                                                <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Financing'))</p>
                                            @endif</td>
                                    </tr>
                                    @else
                                        @php
                                            if($transaction->status == 1 || (!$this->isSameDayCancelled($transaction->order_id) && !$this->isSameDayRefunded($transaction->order_id))){
                                                $totalGrossPrice = $totalGrossPrice + $item->original_price * $item->quantity;
                                                $totalAmount += $item->price * $item->quantity;
                                                $totalDiscount += $item->original_price * $item->quantity - $item->price * $item->quantity;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $item->quantity }}</td>
                                            <td><a href="{{ route('admin.reports.monthly.items', ['product' => $item->product_id]) }}">{{ $this->getProductSupplierCode($item->product_id) }} {{ $item->title }}</a></td>
                                            <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->original_price * $item->quantity)@endif</td>
                                            <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->price * $item->quantity) @endif</td>
                                            <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->original_price * $item->quantity - $item->price * $item->quantity) @endif</td>
                                        </tr>
                                    @endif
                                    @endforeach
    
                                @if($transaction->delivery_fee > 0)
                                    @php
                                        $totalAmount += $transaction->delivery_fee
                                    @endphp
                                    <tr>
                                        <td>1</td>
                                        <td>Delivery Fee</td>
                                        <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($transaction->delivery_fee)@endif</td>
                                        <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($transaction->delivery_fee)@endif</td>
                                        <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money(0)@endif</td>
                                    </tr>
                                @endif
    
                            @empty
                                <tr>
                                    <td colspan="100" align="center">No records found.</td>
                                </tr>
                            @endforelse
    
                            <tr>
                                <td colspan="99"><h4><strong>Collections</strong></h4></td>
                            </tr>
    
                            @forelse($collections as $collection)
                                @php
                                    $receiptType = $this->getReceiptType($collection);
                                @endphp
                            <tr>
                        <td></td>
                        <td><a href="{{ route('admin.order.details', ['order_id' => $collection->order_id]) }}">{{ $receiptType == 'SI' ?  $collection->receipt_number : '' }}</a></td>
                        <td><a href="{{ route('admin.order.details', ['order_id' => $collection->order_id]) }}">{{ $receiptType == 'DR' ?  $collection->receipt_number : '' }}</a></td>
                        <td><a href="{{ route('admin.order.details', ['order_id' => $collection->order_id]) }}">{{ $receiptType == 'CI' ?  $collection->receipt_number : '' }}</a></td>
                        <td><a href="{{ route('admin.order.details', ['order_id' => $collection->order_id]) }}">{{ $receiptType == 'CHG'?  $collection->receipt_number : '' }}</a></td>
                        <td>{{ $collection->or_number }}</td>
                        <td></td>
                        <td>{{ $collection->first_name }} {{ $collection->last_name }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Check'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Bank Transfer'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Card'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Cash'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Gcash'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Financing'))</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="100" align="center">No records found.</td>
                                </tr>
                            @endforelse
    
                            <tr>
                                <td><strong>Totals</strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>@money($totalGrossPrice)</td>
                                <td>@money($totalAmount)</td>
                                <td>@money($totalDiscount)</td>
                                <td><strong>@money($totalCod)</strong></td>
                                <td><strong>@money($this->getPaymentAmountTotal('Check'))</strong></td>
                                <td><strong>@money($this->getPaymentAmountTotal('Bank Transfer'))</strong></td>
                                <td><strong>@money($this->getPaymentAmountTotal('Card'))</strong></td>
                                <td><strong>@money($this->getPaymentAmountTotal('Cash'))</strong></td>
                                <td><strong>@money($this->getPaymentAmountTotal('Gcash'))</strong></td>
                                <td><strong>@money($this->getPaymentAmountTotal('Financing'))</strong></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- PCVs Tab -->
            <div class="tab-pane fade" id="pcv" role="tabpanel" aria-labelledby="pcv-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h3>PCVs</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>PCVs</th>
                                    <th>DISBURSEMENT: Payee-Particulars</th>
                                    <th>Released</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->voucher_number }}</td>
                                    <td>{{ $expense->description }}</td>
                                    <td>{{ $expense->first_name }} {{ $expense->last_name }}</td>
                                    <td>@money($expense->amount)</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100" align="center">No records found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2 text-right">
                                <h4><strong>Total: @money($this->getExpensesTotal())</strong></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    
    </div>
    
    <x-slot:print>
        <div class="printable">
            <table>
                <tr>
                    <td style = "width: 38%">
                        <img src="{{ asset('assets/img/left_logo.png') }}" alt="" style = "width:350px">
                    </td>
                    <td align = "center">
                        <h4><strong>DAILY SALES AND COLLECTION REPORT</strong></h4>
                        <h4><strong>{{ $branch_name ?? '' }} {{ date('F j, Y', strtotime($date)) }}</strong> <small>Page 1 of 1 Printed {{ date('F j h:iA') }}</small></h4>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered receipt-table">
                <tbody>
    
                    <tr>
                        <th rowspan="2">ID</th>
                        <th rowspan="2">SI</th>
                        <th rowspan="2">DR</th>
                        <th rowspan="2">CI</th>
                        <th rowspan="2">CHG</th>
                        <th rowspan="2">CR</th>
                        <th rowspan="2">Representative</th>
                        <th rowspan="2">Customer</th>
                        <th rowspan="2">Unit</th>
                        <th rowspan="2">Particulars</th>
                        <th rowspan="2">Gross Price</th>
                        <th rowspan="2">Total Amount</th>
                        <th rowspan="2">Discount</th>
                        <th rowspan="2">COD</th>
                        <th colspan="5" class="text-center">Mode of Payment</th>
                        <th rowspan="2">Financing</th>
                    </tr>
                    <tr>
                        <th>Check</th>
                        <th>Bank</th>
                        <th>Card</th>
                        <th>Cash</th>
                        <th>Gcash</th>
                    </tr>
                @php
                    $totalGrossPrice = 0;
                    $totalAmount = 0;
                    $totalDiscount = 0;
                    $totalCod = 0;
                @endphp
                @forelse($transactions as $transaction)
                    @php
                        $items = $this->getItems($transaction->order_id);
                        $rowspan = count($items);
    
                        if($transaction->delivery_fee > 0) $rowspan = $rowspan + 1;
                        $receiptType = $this->getReceiptType($transaction);
    
                    @endphp
                    @foreach($items as $item)
                            @if($loop->index == 0)
                            @php
                                $discount = $this->getDiscount($transaction->order_id);
    
                                if($transaction->status == 1 || (!$this->isSameDayCancelled($transaction->order_id) && !$this->isSameDayRefunded($transaction->order_id))){
                                    $totalGrossPrice = $totalGrossPrice + $item->original_price * $item->quantity;
                                    $totalAmount += $item->price * $item->quantity;
                                    $totalDiscount += $item->original_price * $item->quantity - $item->price * $item->quantity;
                                    $totalCod = $totalCod + $this->getPaymentAmount($transaction->transaction_id, 'COD');
                                }
                            @endphp
                            <tr>
                                <td rowspan="{{ $rowspan }}">{{ $transaction->order_number  }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $receiptType == 'SI' ? $transaction->receipt_number : '' }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $receiptType == 'DR' ? $transaction->receipt_number : '' }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $receiptType == 'CI' ? $transaction->receipt_number : '' }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $receiptType == 'CHG'? $transaction->receipt_number : '' }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $receiptType == 'CR' ? $transaction->receipt_number : '' }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $transaction->fname }} {{ $transaction->lname }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $transaction->first_name }} {{ $transaction->last_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $this->getProductSupplierCode($item->product_id) }} {{ $item->title }} </td>
                                <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->original_price * $item->quantity) @endif</td>
                                <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->price * $item->quantity) @endif</td>
                                <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->original_price * $item->quantity - $item->price * $item->quantity) @endif</td>
                                <td rowspan="{{ $rowspan }}">@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($this->getPaymentAmount($transaction->transaction_id, 'COD'))@endif</td>
                                <td rowspan="{{ $rowspan }}">
                                    @if($transaction->cancelled_order_id)
                                        @php
                                            $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Check');
                                        @endphp
    
                                        @if($cancelledInfo['creditedAmount'] > 0)
                                            <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                            {{ $cancelledInfo['receipt'] }}
                                        @endif
                                        @if($cancelledInfo['amount'] > 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                        @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                    @else
                                        <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Check'))</p>
                                    @endif
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    @if($transaction->cancelled_order_id)
                                        @php
                                            $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Bank Transfer');
                                        @endphp
                                         @if($cancelledInfo['creditedAmount'] > 0)
                                            <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                            {{ $cancelledInfo['receipt'] }}
                                        @endif
                                        @if($cancelledInfo['amount'] > 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                        @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                    @else
                                        <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Bank Transfer'))</p>
                                    @endif
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    @if($transaction->cancelled_order_id)
                                        @php
                                            $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Card');
                                        @endphp
                                         @if($cancelledInfo['creditedAmount'] > 0)
                                            <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                            {{ $cancelledInfo['receipt'] }}
                                        @endif
                                        @if($cancelledInfo['amount'] > 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                        @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                    @else
                                        <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Card'))</p>
                                    @endif
                                </td>
                                <td rowspan="{{ $rowspan }}">@if($transaction->cancelled_order_id)
                                        @php
                                            $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Cash');
                                        @endphp
                                         @if($cancelledInfo['creditedAmount'] > 0)
                                            <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                            {{ $cancelledInfo['receipt'] }}
                                        @endif
                                        @if($cancelledInfo['amount'] > 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                        @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                    @else
                                        <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Cash'))</p>
                                    @endif</td>
                                <td rowspan="{{ $rowspan }}">@if($transaction->cancelled_order_id)
                                        @php
                                            $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Gcash');
                                        @endphp
                                        @if($cancelledInfo['creditedAmount'] > 0)
                                            <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                            {{ $cancelledInfo['receipt'] }}
                                        @endif
                                        @if($cancelledInfo['amount'] > 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                        @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                    @else
                                        <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Gcash'))</p>
                                    @endif</td>
                                <td rowspan="{{ $rowspan }}">@if($transaction->cancelled_order_id)
                                        @php
                                            $cancelledInfo = $this->getCancelledPaymentAmount($transaction->order_id, $transaction->cancelled_order_id, 'Financing');
                                        @endphp
                                         @if($cancelledInfo['creditedAmount'] > 0)
                                            <p style="color: red;">@money($cancelledInfo['creditedAmount'])</p>
                                            {{ $cancelledInfo['receipt'] }}
                                        @endif
                                        @if($cancelledInfo['amount'] > 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                        @if($cancelledInfo['amount'] == 0 && $cancelledInfo['creditedAmount'] == 0)
                                            @money($cancelledInfo['amount'])
                                        @endif
                                    @else
                                        <p @if($this->isVoid($transaction)) style="color: red;" @endif>@money($this->getPaymentAmount($transaction->transaction_id, 'Financing'))</p>
                                    @endif</td>
                            </tr>
                        @else
                            @php
                                if($transaction->status == 1 || (!$this->isSameDayCancelled($transaction->order_id) && !$this->isSameDayRefunded($transaction->order_id))){
                                    $totalGrossPrice = $totalGrossPrice + $item->original_price * $item->quantity;
                                    $totalAmount += $item->price * $item->quantity;
                                    $totalDiscount += $item->original_price * $item->quantity - $item->price * $item->quantity;
                                }
                            @endphp
                            <tr>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $this->getProductSupplierCode($item->product_id) }} {{ $item->title }}</td>
                                <td>@money($item->original_price * $item->quantity)</td>
                                <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->price * $item->quantity) @endif</td>
                                <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($item->original_price * $item->quantity - $item->price * $item->quantity) @endif</td>
                            </tr>
                        @endif
                    @endforeach
    
                    @if($transaction->delivery_fee > 0)
                        @php
                            $totalAmount += $transaction->delivery_fee
                        @endphp
                        <tr>
                            <td>1</td>
                            <td>Delivery Fee</td>
                            <td>@if($transaction->status == 3 && $this->isSameDayCancelled($transaction->order_id)) Cancelled @elseif($transaction->status == 4 && $this->isSameDayRefunded($transaction->order_id)) Refunded @else @money($transaction->delivery_fee)@endif</td>
                        </tr>
                    @endif
    
                @empty
                    <tr>
                        <td colspan="100" align="center">No records found.</td>
                    </tr>
                @endforelse
    
                    <tr>
                        <td colspan="99"><h4 style = "margin:0;"><strong>Collections</strong></h4></td>
                    </tr>
    
                @forelse($collections as $collection)
                    @php
                        $receiptType = $this->getReceiptType($collection);
                    @endphp
                    <tr>
                        <td></td>
                        <td>{{ $receiptType == 'SI' ?  $collection->receipt_number : '' }}</td>
                        <td>{{ $receiptType == 'DR' ?  $collection->receipt_number : '' }}</td>
                        <td>{{ $receiptType == 'CI' ?  $collection->receipt_number : '' }}</td>
                        <td>{{ $receiptType == 'CHG'?  $collection->receipt_number : '' }}</td>
                        <td>{{ $collection->or_number }}</td>
                        <td></td>
                        <td>{{ $collection->first_name }} {{ $collection->last_name }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Check'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Bank Transfer'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Card'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Cash'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Gcash'))</td>
                        <td>@money($this->getPaymentAmount($collection->transaction_id, 'Financing'))</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100" align="center">No records found.</td>
                    </tr>
                @endforelse
    
                <tr>
                    <td><strong>Totals</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>@money($totalGrossPrice)</td>
                    <td>@money($totalAmount)</td>
                    <td>@money($totalDiscount)</td>
                    <td><strong>@money($totalCod)</strong></td>
                    <td><strong>@money($this->getPaymentAmountTotal('Check'))</strong></td>
                    <td><strong>@money($this->getPaymentAmountTotal('Bank Transfer'))</strong></td>
                    <td><strong>@money($this->getPaymentAmountTotal('Card'))</strong></td>
                    <td><strong>@money($this->getPaymentAmountTotal('Cash'))</strong></td>
                    <td><strong>@money($this->getPaymentAmountTotal('Gcash'))</strong></td>
                    <td><strong>@money($this->getPaymentAmountTotal('Financing'))</strong></td>
                </tr>
                    {{-- Total --}}
    
    
                    <tr>
                        <th colspan = "3">PCVs</th>
                        <th colspan = "10">DISBURSEMENT: Payee-Particulars</th>
                        <th colspan = "3">Released</th>
                        <th colspan = "3">Amount</th>
                    </tr>
                    @forelse($expenses as $expense)
                        <tr>
                            <td colspan = "3">{{ $expense->voucher_number }}</td>
                            <td colspan = "10">{{ $expense->description }}</td>
                            <td colspan = "3">{{ $expense->first_name }} {{ $expense->last_name }}</td>
                            <td colspan = "3">@money($expense->amount)</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100" align="center">No records found.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="99">
                            <div class="text-right">
                                <h5><strong>Total: @money($this->getExpensesTotal())</strong></h5>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-slot>
    
    @assets
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .table-responsive {
                height: 100vh; /* Define a height */
                overflow-y: auto;
            }
            .table-responsive thead th {
                position: sticky;
                top: 0;
                z-index: 1; /* Ensure the sticky header stays above other content */
                vertical-align: middle !important;
            }
            .table-responsive thead th[rowspan="2"] {
                top: 0; /* Sticky header starts at the top */
            }
    
            .table-responsive thead th:not([rowspan="2"]) {
                top: 40px; /* Second row headers start 40px below the top */
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
            @media screen {
                .receipt-table thead th {
                    position: sticky;
                    top: 0;
                    background-color: white; /* Ensure the background of the header is visible */
                    z-index: 1; /* Make sure the header stays on top */
                    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); /* Optional: Adds a subtle shadow for better visual separation */
                }
            }
            @media print {
                @page {
                    margin: 1cm; /* Set margins to ensure no content is cut off */
                }
    
                .no-print {
                    display: none;
                }
    
                .printable {
                    display: block;
                }
    
                .receipt-table{
                    font-size: 12px;
                }
                .receipt-table th,td{
                    padding:1px;
                    vertical-align: middle;
                }
    
                /* Apply a scaling factor to fit the table within the page */
                body {
                    transform: scale(0.85); /* Scale down the entire content */
                    transform-origin: top left; /* Ensure scaling starts from top-left corner */
                }
    
            }
        </style>
    @endassets
    
    @script
        <script>
            $('#datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    weekStart: 1,
                    daysOfWeekHighlighted: "6,0",
                    autoclose: true,
                    todayHighlight: true,
            }).on('change', function (){
                var date = $(this).val();
                $wire.dispatch('date-set', {date: date});
            });
    
            $wire.on('date-changed', (event) => {
                $('#datepicker').datepicker('setDate', $wire.get('date'));
            });
        </script>
    @endscript
    