<div>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 mb-2 text-primary admin-title"><strong>Monthly Items Reports</strong></h1>
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
                    <div class="col-md-8">

                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Date</div>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button wire:click="changeDate('decrement')" type="button" class="btn btn-primary">
                                <i class="fas fa-chevron-left"></i></button>

                            <input class="form-control" value="{{ $date ?? date('Y-m') }}" id="datepicker"
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
               aria-controls="sales" aria-selected="true">Month</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab"
               aria-controls="payments" aria-selected="false">Daily Tally</a>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">
        <!-- Expenses Tab -->
        <div class="tab-pane fade show active" id="expenses" role="tabpanel" aria-labelledby="sales-tab">
        <div class="card shadow mb-4">
            <div class="card-body p-0">
                <table class="table ">
                    <thead style="background-color: #f4b083;color:#fff">
                    <tr>
                        <th>Item Name: {{ $product->model }} {{ $product->description }}</th>
                        <th>Opening Quantity: {{ $opening_quantity }}</th>
                        <th>Closing Quantity: {{ is_null($closing_quantity) ? $opening_quantity : $closing_quantity }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="table-a text-center" style = "background-color: #8eaadb;color:#FFF">
                        <th colspan="4">SOLD</th>
                    </tr>
                    <tr>
                        <th>Quantity:</th>
                        <th>Cashier:</th>
                        <th>Receipt:</th>
                        <th>Date/Time:</th>
                    </tr>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->assistant_first_name }} {{ $order->assistant_last_name }}</td>
                            <td><a wire:navigate href="{{ route('admin.order.details', ['order_id' => $order->order_id]) }}">{{ $order->receipt_number }}</a></td>
                            <td>{{ date('h:ia F j, Y', strtotime($order->completed_at)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No sold items found.</td>
                        </tr>
                    @endforelse
                    <tr class="table-a text-center" style = "background-color: #8eaadb;color:#FFF">
                        <th colspan="4">RECEIVED</th>
                    </tr>
                    <tr>
                        <th>Quantity:</th>
                        <th>Reference Number:</th>
                        <th>From:</th>
                        <th>Date/Time:</th>
                    </tr>
                    @php
                        $totalReceived = 0;
                    @endphp
                    @forelse($receives as $receive)
                        @php
                            $reference = $this->getReference($receive->quantity, $receive->date);
                            $totalReceived += $receive->quantity;
                        @endphp
                        <tr>
                            <td>{{ $receive->quantity }}</td>
                            <td>@if($reference['link']) <a href="{{ $reference['link'] }}">{{ $reference['number'] }}</a> @else N/A @endif</td>
                            <td>{{ $reference['from'] }}</td>
                            <td>{{ date('h:ia F j, Y', strtotime($receive->date)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No data found.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td>Total: {{ $totalReceived }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="table-a text-center" style = "background-color: #8eaadb;color:#FFF">
                        <th colspan="4">TRANSFERRED</th>
                    </tr>
                    <tr>
                        <th>Quantity:</th>
                        <th>To:</th>
                        <th>Date/Time:</th>
                        <th></th>
                    </tr>
                    @php
                        $totalTransfer = 0;
                    @endphp
                    @forelse($transfers as $transfer)
                        @php
                            $totalTransfer += $transfer->transferred
                        @endphp
                        <tr>
                            <td>{{ $transfer->transferred }}</td>
                            <td>{{ $transfer->name }}</td>
                            <td>{{ date('h:ia F j, Y', strtotime($transfer->created_at)) }}</td>
                            <td></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No data found.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td>Total: {{ $totalTransfer }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

        <!-- Payments Tab -->
        <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">

            <div class="card shadow mb-4">
                <div class="card-body">

                    <h3>{{ $product->model }} {{ $product->description }}</h3>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Day</th>
                            <th>Opening Count</th>
                            <th>Closing Count</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            use Carbon\Carbon;

                            $startOfMonth = Carbon::createFromFormat('Y-m', $date)->startOfMonth();
                            $endOfMonth = Carbon::createFromFormat('Y-m', $date)->endOfMonth();
                        @endphp
                        @foreach (Carbon::parse($startOfMonth)->daysUntil($endOfMonth) as $day)
                            @php
                                $opening = $this->getDailyOpeningQuantity($day->format('Y-m-d'));
                                $closing = $this->getDailyClosingQuantity($day->format('Y-m-d'));
                                $closing = is_null($closing) ? $opening : $closing;
                            @endphp
                        <tr>
                            @if($day->format('F j, Y') == $startOfMonth->format('F j, Y') || $day->format('F j, Y') == $endOfMonth->format('F j, Y') || $opening != $closing)
                            <td>{{ $day->format('F j, Y') }}</td>
                            <td>{{ $this->getDailyOpeningQuantity($day->format('Y-m-d')) }}</td>
                            <td>{{ $this->getDailyClosingQuantity($day->format('Y-m-d')) }}</td>
                            @endif
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
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
                    <h6><strong>Daily Items Reports</strong></h6>
                    <h6><strong>Date: {{ date('F j h:iA') }}</strong></h6>
                </td>
            </tr>
        </table>
        <table class="table table-bordered receipt-table">
            <thead>
            <tr >
                <th style="padding:10px;">Item Name: {{ $product->model }} {{ $product->description }}</th>
                <th style="padding:10px;">Opening Quantity: {{ $opening_quantity }}</th>
                <th style="padding:10px;">Closing Quantity: {{ is_null($closing_quantity) ? $opening_quantity : $closing_quantity }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr class="table-a text-center" style = "background-color: #8eaadb;color:#FFF">
                <th colspan="4">SOLD</th>
            </tr>
            <tr>
                <th>Quantity:</th>
                <th>Cashier:</th>
                <th>Receipt:</th>
                <th>Date/Time:</th>
            </tr>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->assistant_first_name }} {{ $order->assistant_last_name }}</td>
                    <td><a wire:navigate href="{{ route('admin.order.details', ['order_id' => $order->order_id]) }}">{{ $order->receipt_number }}</a></td>
                    <td>{{ date('h:ia F j, Y', strtotime($order->completed_at)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No sold items found.</td>
                </tr>
            @endforelse
            <tr class="table-a text-center" style = "background-color: #8eaadb;color:#FFF">
                <th colspan="4">RECEIVED</th>
            </tr>
            <tr>
                <th>Quantity:</th>
                <th>Reference Number:</th>
                <th>From:</th>
                <th>Date/Time:</th>
            </tr>
            @php
                $totalReceived = 0;
            @endphp
            @forelse($receives as $receive)
                @php
                    $reference = $this->getReference($receive->quantity, $receive->date);
                    $totalReceived += $receive->quantity;
                @endphp
                <tr>
                    <td>{{ $receive->quantity }}</td>
                    <td>@if($reference['link']) <a href="{{ $reference['link'] }}">{{ $reference['number'] }}</a> @else N/A @endif</td>
                    <td>{{ $reference['from'] }}</td>
                    <td>{{ date('h:ia F j, Y', strtotime($receive->date)) }}</td>z
                </tr>
            @empty
                <tr>
                    <td colspan="10">No data found.</td>
                </tr>
            @endforelse
            <tr>
                <td>Total: {{ $totalReceived }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="table-a text-center" style = "background-color: #8eaadb;color:#FFF">
                <th colspan="4">TRANSFERRED</th>
            </tr>
            <tr>
                <th>Quantity:</th>
                <th>To:</th>
                <th>Date/Time:</th>
                <th></th>
            </tr>
            @php
                $totalTransfer = 0;
            @endphp
            @forelse($transfers as $transfer)
                @php
                    $totalTransfer += $transfer->transferred
                @endphp
                <tr>
                    <td>{{ $transfer->transferred }}</td>
                    <td>{{ $transfer->name }}</td>
                    <td>{{ date('h:ia F j, Y', strtotime($transfer->created_at)) }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No data found.</td>
                </tr>
            @endforelse
            <tr>
                <td>Total: {{ $totalTransfer }}</td>
                <td></td>
                <td></td>
                <td></td>
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
        font-size: 12px;
        border:1px solid #000 !important;
    }

    .receipt-table th,
    .receipt-table td {
        border:1px solid #000 !important;
        padding: 8px !important;
        text-align: left;
        color:#000;
        font-weight: 700;
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
        @page {
            size: landscape; /* Ensures the page is printed in landscape */
            margin: 1cm; /* Set margins to ensure no content is cut off */
        }

        .no-print {
            display: none;
        }

        .printable {
            display: block;
        }

        .receipt-table{
            font-size: 10px;
        }
        .receipt-table th,td{
            padding:1px;
        }
        /* Apply a scaling factor to fit the table within the page */
        body {
            transform: scale(0.99); /* Scale down the entire content */
            transform-origin: top left; /* Ensure scaling starts from top-left corner */
        }
    }
</style>
@endassets
@script
<script>
    $('#datepicker').datepicker({
        format: 'yyyy-mm',
        minViewMode: 1,
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
