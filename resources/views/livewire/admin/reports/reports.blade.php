<div>
<div class="container-fluid no-print">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 mb-2 text-primary admin-title">Reports</h1>
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
            </div>

        </div>
    </div>


    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="true">Sales</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="collections-tab" data-toggle="tab" href="#collections" role="tab" aria-controls="collections" aria-selected="false">Collections</a>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">ID</th>
                                <th rowspan="2">SI</th>
                                <th rowspan="2">DR</th>
                                <th rowspan="2">CI</th>
                                <th rowspan="2">CR</th>
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
                        </thead>
                        <tbody>
                        @forelse($transactions as $transaction)
                            @php
                            $items = $this->getItems($transaction->order_id);
                            $rowspan = count($items);
                            @endphp
                                @foreach($items as $item)
                                @if($loop->index == 0)
                                <tr>
                                    <td rowspan="{{ $rowspan }}">{{ $transaction->order_number  }}</td>
                                    <td rowspan="{{ $rowspan }}">SI-001</td>
                                    <td rowspan="{{ $rowspan }}">DR-001</td>
                                    <td rowspan="{{ $rowspan }}">CI-001</td>
                                    <td rowspan="{{ $rowspan }}">CR-001</td>
                                    <td rowspan="{{ $rowspan }}">{{ $transaction->first_name }} {{ $transaction->last_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>@money($item->price)</td>
                                    <td rowspan="{{ $rowspan }}">$950</td>
                                    <td rowspan="{{ $rowspan }}">$50</td>
                                    <td rowspan="{{ $rowspan }}">$950</td>
                                    <td rowspan="{{ $rowspan }}">$500</td>
                                    <td rowspan="{{ $rowspan }}">$300</td>
                                    <td rowspan="{{ $rowspan }}">$50</td>
                                    <td rowspan="{{ $rowspan }}">$100</td>
                                    <td rowspan="{{ $rowspan }}">$0</td>
                                    <td rowspan="{{ $rowspan }}">0%</td>
                                </tr>
                                @else
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>@money($item->price)</td>
                                @endif
                                @endforeach
                        @empty
                            <tr>
                                <td colspan="100" align="center">No records found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Collections Tab -->
        <div class="tab-pane fade" id="collections" role="tabpanel" aria-labelledby="collections-tab">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h3>Collections</h3>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>119</td>
                            <td>28100</td>
                            <td>Golden Tuscano</td>
                            <td></td>
                            <td>AR#28100</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>26,400</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>119</td>
                            <td>28100</td>
                            <td>Golden Tuscano</td>
                            <td></td>
                            <td>AR#28100</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>26,400</td>
                            <td></td>
                            <td></td>
                        </tr>

                        {{-- Total --}}
                        <tr>
                            <td><strong>Totals</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>138,860</td>
                            <td>128,947</td>
                            <td>9,913</td>
                            <td><strong>0.00</strong></td>
                            <td><strong>65,840.00</strong></td>
                            <td><strong>0.00</strong></td>
                            <td><strong>0.00</strong></td>
                            <td><strong>136,697.00</strong></td>
                            <td><strong>0.00</strong></td>
                            <td><strong>23,804.00</strong></td>
                        </tr>
                        {{-- Total --}}
                        </tbody>
                    </table>
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
                            <tr>
                                <td>PCV-001</td>
                                <td>John Doe - Services Rendered</td>
                                <td>2024-09-29</td>
                                <td>$500</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2 text-right">
                            <h4><strong>Total: $500.00</strong></h4>
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
                    <h4><strong>Jenny Grace Sta Maria August 30, 2024</strong> <small>Page 1 of 1 Printed August 31 3:25PM</small></h4>
                </td>
            </tr>
        </table>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">SI</th>
                    <th rowspan="2">DR</th>
                    <th rowspan="2">CI</th>
                    <th rowspan="2">CR</th>
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
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>SI-001</td>
                    <td>DR-001</td>
                    <td>CI-001</td>
                    <td>CR-001</td>
                    <td>John Doe</td>
                    <td>Unit A</td>
                    <td>Item 1</td>
                    <td>$1000</td>
                    <td>$950</td>
                    <td>$50</td>
                    <td>$950</td>
                    <td>$500</td>
                    <td>$300</td>
                    <td>$50</td>
                    <td>$100</td>
                    <td>$0</td>
                    <td>0%</td>
                </tr>

                <tr>
                    <td colspan="99"><h4><strong>Collections</strong></h4></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>119</td>
                    <td>28100</td>
                    <td>Golden Tuscano</td>
                    <td></td>
                    <td>AR#28100</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>26,400</td>
                    <td></td>
                    <td></td>
                </tr>

                {{-- Total --}}
                <tr>
                    <td><strong>Totals</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>138,860</td>
                    <td>128,947</td>
                    <td>9,913</td>
                    <td><strong>0.00</strong></td>
                    <td><strong>65,840.00</strong></td>
                    <td><strong>0.00</strong></td>
                    <td><strong>0.00</strong></td>
                    <td><strong>136,697.00</strong></td>
                    <td><strong>0.00</strong></td>
                    <td><strong>23,804.00</strong></td>
                </tr>
                {{-- Total --}}
            </tbody>
        </table>


        <!-- New Disbursement Table -->
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
                <tr>
                    <td>PCV-001</td>
                    <td>John Doe - Services Rendered</td>
                    <td>2024-09-29</td>
                    <td>$500</td>
                </tr>
                <!-- Add more rows here as needed -->
            </tbody>
        </table>

        <!-- Total Amount Text -->
        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2 text-right">
                <h4><strong>Total: $500.00</strong></h4>
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
        $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                daysOfWeekHighlighted: "6,0",
                autoclose: true,
                todayHighlight: true,
        });

        $wire.on('date-changed', (event) => {
            $('#datepicker').datepicker('setDate', $wire.get('date'));
        });
    </script>
@endscript
