<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Adjusted Products</strong></h1>
                </div>
                <div class="col-md-2">
                    <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-outline-secondary no-print" onclick="window.print()"><i class="fas fa-print"></i> Print Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="float-left form-control" placeholder="Search..."
                               wire:model.live="search">
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

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                <tr class="bg-secondary font-w">
                    <th>Supplier</th>
                    <th>SKU</th>
                    <th>Model</th>
                    <th>Description</th>
                    <th>Branch</th>
                    <th>Action</th>
                    <th>Quantity</th>
                    <th>Running Available</th>
                    <th>Running Reserved</th>
                    <th>Running Damaged</th>
                    <th>Running Sold</th>
                    <th>User</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($histories as $history)
                    <tr>
                        <td>{{ $history->code }}</td>
                        <td>{{ $history->sku_number }}</td>
                        <td>{{ $history->model }}</td>
                        <td>{{ $history->description }}</td>
                        <td>{{ $history->name }}</td>
                        <td>{{ $history->action }}</td>
                        <td>{{ $history->quantity }}</td>
                        <td>{{ $history->running_available }}</td>
                        <td>{{ $history->running_reserved }}</td>
                        <td>{{ $history->running_damaged }}</td>
                        <td>{{ $history->running_sold }}</td>
                        <td>{{ $history->first_name }} {{ $history->last_name }}</td>
                        <td>{{ date('h:i a F j, Y', strtotime($history->date)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">No data found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $histories->links() }}
        </div>
    </div>
</div>

<!-- Printable Section -->
<x-slot:print>
    <div class="printable">
        <table>
            <tr>
                <td style="width: 38%">
                    <img src="{{ asset('assets/img/left_logo.png') }}" alt="" style="width:350px">
                </td>
                <td align="center">
                    <h4><strong>PRODUCTS WITH QUANTITY REPORT</strong></h4>
                    <h4>
                        <strong>{{ $branch_name ?? 'All Branches' }} - {{ date('F j, Y', strtotime($date)) }}</strong>
                        <small>Page 1 of 1 Printed {{ date('F j h:iA') }}</small>
                    </h4>
                </td>
            </tr>
        </table>

        <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
            <thead>
            <tr class="bg-secondary font-w">
                <th>Supplier</th>
                <th>SKU</th>
                <th>Model</th>
                <th>Description</th>
                <th>Branch</th>
                <th>Action</th>
                <th>Quantity</th>
                <th>Running Available</th>
                <th>Running Reserved</th>
                <th>Running Damaged</th>
                <th>Running Sold</th>
                <th>User</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $history)
                <tr>
                    <td>{{ $history->code }}</td>
                    <td>{{ $history->sku_number }}</td>
                    <td>{{ $history->model }}</td>
                    <td>{{ $history->description }}</td>
                    <td>{{ $history->name }}</td>
                    <td>{{ $history->action }}</td>
                    <td>{{ $history->quantity }}</td>
                    <td>{{ $history->running_available }}</td>
                    <td>{{ $history->running_reserved }}</td>
                    <td>{{ $history->running_damaged }}</td>
                    <td>{{ $history->running_sold }}</td>
                    <td>{{ $history->first_name }} {{ $history->last_name }}</td>
                    <td>{{ date('h:i a F j, Y', strtotime($history->date)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13">No data found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-slot>

</div>

@assets
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .no-print {
        display: inline;
    }

    .printable {
        display: none;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        body {
            transform: scale(0.95);
            transform-origin: top left;
        }

        .no-print {
            display: none;
        }

        .printable {
            display: block;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .receipt-table th, .receipt-table td {
            padding: 8px;
            border: 1px solid black;
            text-align: left;
        }

        .receipt-table th {
            background-color: #f2f2f2;
        }
    }
</style>
@endassets
