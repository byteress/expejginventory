@php use Carbon\Carbon; @endphp
<div>
    <div class="container-fluid no-print">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 mb-2 text-primary admin-title"><strong>Monthly Reports</strong></h1>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex justify-content-end">
                            <a href="#" class="btn btn-outline-secondary" onclick="window.print()"><i
                                    class="fas fa-print"></i> Print Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">

                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Date
                        </div>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button wire:click="changeDate('decrement')" type="button" class="btn btn-primary">
                                <i class="fas fa-chevron-left"></i></button>

                            <input class="form-control" value="{{ $date ?? date('Y-m') }}" id="monthpicker"
                                   style="border-radius: 0;">

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
                                <option value="{{ $branch->id }}" @if (auth()->user()->branch_id == $branch->id)
                                    selected @endif>
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
            <a class="nav-link active" id="expenses-tab" data-toggle="tab" href="#expenses" role="tab"
               aria-controls="sales" aria-selected="true">Expenses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab"
               aria-controls="payments" aria-selected="false">Payments</a>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">
        <!-- Expenses Tab -->
        <div class="tab-pane fade show active" id="expenses" role="tabpanel" aria-labelledby="sales-tab">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h3>Monthly Income</h3>
                    <!-- Monthly Expenses table content -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Day</th>
                                <th>Total Payment</th>
                                <th>Expenses</th>
                                <th>Total</th>
                            </tr>
                            <tbody>
                            @php

                                $totalAmount = 0;
                                $dailyExpenses = $this->getDailyExpenses();
                                $currentMonth = $this->date ?? now()->format('Y-m');

                                $currentDate = Carbon::createFromFormat('Y-m', $currentMonth);

                                $daysInMonth = $currentDate->daysInMonth;
                            @endphp

                            @foreach(range(1, $daysInMonth) as $day)
                                @php
                                    $dailyTotalAmount = 0;
                                    $dailyExpensesAmount = 0;

                                    if ($day > $daysInMonth) {
                                        continue;
                                    }

                                    $date = $currentDate->copy()->setDay($day);
                                @endphp

                                @foreach ($transactions as $transaction)
                                    @php
                                        $createdAt = Carbon::parse($transaction->creation_date);
                                        $items = $this->getItems($transaction->order_id);
                                    @endphp

                                    @if (
                                        $createdAt->year == $currentDate->year &&
                                        $createdAt->month == $currentDate->month &&
                                        $createdAt->day == $day
                                    )
                                        @if (
                                            $transaction->status == 1 ||
                                            (
                                                !$this->isSameDayCancelled($transaction->order_id) &&
                                                !$this->isSameDayRefunded($transaction->order_id)
                                            )
                                        )
                                            @foreach ($items as $item)
                                                @if ($loop->index == 0)
                                                    @php
                                                        $dailyTotalAmount += $item->price * $item->quantity;
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if ($transaction->delivery_fee > 0)
                                                @php
                                                    $dailyTotalAmount += $transaction->delivery_fee;
                                                @endphp
                                            @endif
                                        @endif
                                    @endif
                                @endforeach

                                @php
                                    $expenseData = $dailyExpenses->firstWhere('day', $day);
                                    $dailyExpensesAmount = $expenseData ? $expenseData->total_expenses : 0;
                                @endphp

                                @if($dailyTotalAmount > 0 || $dailyExpensesAmount > 0)
                                    <tr>
                                        <td>{{ $date->format('m/d/Y') }}</td>
                                        <td>{{ money($dailyTotalAmount) }}</td>
                                        <td>{{ money($dailyExpensesAmount) }}</td>
                                        <td>{{ money($dailyTotalAmount - $dailyExpensesAmount) }}</td>
                                    </tr>

                                    @php
                                        $totalAmount += ($dailyTotalAmount - $dailyExpensesAmount);
                                    @endphp
                                @endif
                            @endforeach

                            <tr class="font-weight-bold">
                                <td colspan="3"><strong>Total</strong></td>
                                <td>{{ money($totalAmount) }}</td>
                            </tr>
                            </tbody>

                        </table>


                    </div>

                </div>
            </div>
        </div>

        <!-- Payments Tab -->
        <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
            <div class="card shadow mb-4">
                <div class="card-body">

                    <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="check-tab" data-toggle="tab" href="#check" role="tab"
                               aria-controls="check" aria-selected="true">Check</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab"
                               aria-controls="bank" aria-selected="true">Bank</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="card-tab" data-toggle="tab" href="#card" role="tab"
                               aria-controls="card" aria-selected="true">Card</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="cash-tab" data-toggle="tab" href="#cash" role="tab"
                               aria-controls="cash" aria-selected="true">Cash</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="gcash-tab" data-toggle="tab" href="#gcash" role="tab"
                               aria-controls="gcash" aria-selected="true">GCash</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="paymentTabsContent">
                        <!-- Check Payments -->
                        <div class="tab-pane fade show active" id="check" role="tabpanel"
                             aria-labelledby="check-tab">
                            <h3>Check Payments</h3>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Customer Name</th>
                                    <th>Reference Number</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_check_payment = 0;
                                @endphp
                                @foreach($checkPayments as $payment)
                                    @php
                                        $total_check_payment += $payment->amount
                                    @endphp
                                    <tr>
                                        <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                                        <td>{{ $payment->customer_name }}</td>
                                        <td>{{ $payment->reference }}</td>
                                        <td>@money($payment->amount)</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>@money($total_check_payment)</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Repeat similar structure for other payment types -->
                        <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                            <h3>Bank Payments</h3>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Customer Name</th>
                                    <th>Reference Number</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_bank_payment = 0;
                                @endphp
                                @foreach($bankPayments as $payment)
                                    @php
                                        $total_bank_payment += $payment->amount
                                    @endphp
                                    <tr>
                                        <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                                        <td>{{ $payment->customer_name }}</td>
                                        <td>{{ $payment->reference }}</td>
                                        <td>@money($payment->amount)</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>@money($total_bank_payment)</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Card Payments -->
                        <div class="tab-pane fade" id="card" role="tabpanel" aria-labelledby="card-tab">
                            <h3>Card Payments</h3>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Customer Name</th>
                                    <th>Reference Number</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_card_payment = 0;
                                @endphp
                                @foreach($cardPayments as $payment)
                                    @php
                                        $total_card_payment += $payment->amount;
                                    @endphp
                                    <tr>
                                        <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                                        <td>{{ $payment->customer_name }}</td>
                                        <td>{{ $payment->reference }}</td>
                                        <td>@money($payment->amount)</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>@money($total_card_payment)</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Cash Payments -->
                        <div class="tab-pane fade" id="cash" role="tabpanel" aria-labelledby="cash-tab">
                            <h3>Cash Payments</h3>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Customer Name</th>
                                    <th>Reference Number</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_cash_payment = 0;
                                @endphp
                                @foreach($cashPayments as $payment)
                                    @php
                                        $total_cash_payment += $payment->amount;
                                    @endphp
                                    <tr>
                                        <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                                        <td>{{ $payment->customer_name }}</td>
                                        <td>{{ $payment->reference }}</td>
                                        <td>@money($payment->amount)</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>@money($total_cash_payment)</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- GCash Payments -->
                        <div class="tab-pane fade" id="gcash" role="tabpanel" aria-labelledby="gcash-tab">
                            <h3>GCash Payments</h3>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Customer Name</th>
                                    <th>Reference Number</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_gcash_payment = 0;
                                @endphp
                                @foreach($gcashPayments as $payment)
                                    @php
                                        $total_gcash_payment += $payment->amount;
                                        @endphp
                                        <tr>
                                            <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                                            <td>{{ $payment->customer_name }}</td>
                                            <td>{{ $payment->reference }}</td>
                                            <td>@money($payment->amount)</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td><strong>@money($total_gcash_payment)</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
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
                    <td style="width: 38%">
                        <img src="{{ asset('assets/img/left_logo.png') }}" alt="" style="width:350px">
                    </td>
                    <td align="center">
                        <h4><strong>MONTHLY SALES AND PAYMENT METHOD REPORT</strong></h4>
                        <h4><strong>{{ $branch_name ?? '' }} {{ date('F j, Y', strtotime($date)) }}</strong> <small>Page 1
                                of 1 Printed {{ date('F j h:iA') }}</small></h4>
                    </td>
                </tr>
            </table>

            <!-- Expenses Table -->
            <h3>Monthly Income</h3>
            <table class="table table-bordered receipt-table">
                <thead>
                <tr>
                    <th>Day</th>
                    <th>Total Payment</th>
                    <th>Expenses</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $dailyPayments = $this->getDailyPaymentAmountTotal();
                    $dailyExpenses = $this->getDailyExpenses();
                    $total = 0;

                    $currentMonth = $this->date ?? now()->format('Y-m');
                    $daysInMonth = Carbon::createFromFormat('Y-m', $currentMonth)->daysInMonth;
            @endphp

            @foreach(range(1, $daysInMonth) as $day)
                @php
                    $fullDate = Carbon::createFromFormat('Y-m-d',
                    "{$currentMonth}-{$day}")->format('m/d/Y');
                    $payment = $dailyPayments->firstWhere('day', $day)->total ?? 0;
                    $expense = $dailyExpenses->firstWhere('day', $day)->total_expenses ?? 0;
                    $subtotal = $payment - $expense;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $fullDate }}</td>
                    <td>@money($payment)</td>
                    <td>@money($expense)</td>
                    <td>@money($subtotal)</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td>@money($total)</td>
            </tr>
            </tbody>
        </table>

        <!-- Payments Section -->
        <h3>Payments</h3>

        <!-- Check Payments -->
        <h4>Check Payments</h4>
        <table class="table table-bordered receipt-table">
            <thead>
            <tr>
                <th>Day</th>
                <th>Customer Name</th>
                <th>Reference Number</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($checkPayments as $payment)
                <tr>
                    <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                    <td>{{ $payment->customer_name }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>@money($payment->amount)</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Bank Payments -->
        <h4>Bank Payments</h4>
        <table class="table table-bordered receipt-table">
            <thead>
            <tr>
                <th>Day</th>
                <th>Customer Name</th>
                <th>Reference Number</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bankPayments as $payment)
                <tr>
                    <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                    <td>{{ $payment->customer_name }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>@money($payment->amount)</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Card Payments -->
        <h4>Card Payments</h4>
        <table class="table table-bordered receipt-table">
            <thead>
            <tr>
                <th>Day</th>
                <th>Customer Name</th>
                <th>Reference Number</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cardPayments as $payment)
                <tr>
                    <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                    <td>{{ $payment->customer_name }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>@money($payment->amount)</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Cash Payments -->
        <h4>Cash Payments</h4>
        <table class="table table-bordered receipt-table">
            <thead>
            <tr>
                <th>Day</th>
                <th>Customer Name</th>
                <th>Reference Number</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cashPayments as $payment)
                <tr>
                    <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                    <td>{{ $payment->customer_name }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>@money($payment->amount)</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- GCash Payments -->
        <h4>GCash Payments</h4>
        <table class="table table-bordered receipt-table">
            <thead>
            <tr>
                <th>Day</th>
                <th>Customer Name</th>
                <th>Reference Number</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($gcashPayments as $payment)
                <tr>
                    <td>{{ Carbon::parse($payment->date)->format('m/d/Y') }}</td>
                    <td>{{ $payment->customer_name }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>@money($payment->amount)</td>
                </tr>
            @endforeach
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
        height: 100vh;
        /* Define a height */
        overflow-y: auto;
    }

    .table-responsive thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        /* Ensure the sticky header stays above other content */
        vertical-align: middle !important;
    }

    .table-responsive thead th[rowspan="2"] {
        top: 0;
        /* Sticky header starts at the top */
    }

    .table-responsive thead th:not([rowspan="2"]) {
        top: 40px;
        /* Second row headers start 40px below the top */
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
        max-width: 250px;
        word-wrap: break-word;
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
            background-color: white;
            /* Ensure the background of the header is visible */
            z-index: 1;
            /* Make sure the header stays on top */
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow for better visual separation */
        }
    }

    @media print {
        @page {
            margin: 1cm;
            /* Set margins to ensure no content is cut off */
        }

        .no-print {
            display: none;
        }

        .printable {
            display: block;
        }

        .receipt-table {
            font-size: 12px;
        }

        .receipt-table th,
        td {
            padding: 1px;
            vertical-align: middle;
        }

        /* Apply a scaling factor to fit the table within the page */
        body {
            transform: scale(0.85);
            /* Scale down the entire content */
            transform-origin: top left;
            /* Ensure scaling starts from top-left corner */
        }

    }
</style>
@endassets

@script
<script>
    $('#monthpicker').datepicker({
        format: 'yyyy-mm',
        minViewMode: 1,
        autoclose: true,
        todayHighlight: true,
    }).on('change', function () {
        var date = $(this).val();
        if (date) {
            $wire.dispatch('date-set', {
                date: date
            });
        }
    });

    $wire.on('date-changed', (event) => {
        const livewireDate = $wire.get('date');
        if (livewireDate) {
            $('#monthpicker').datepicker('setDate', livewireDate);
        } else {
            $('#monthpicker').datepicker('clearDates');
        }
    });
</script>
@endscript
