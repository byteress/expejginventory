<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Sold out Products</strong></h1>
                </div>
                <div class="col-md-2">
                    <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Reports
                        </a>
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
                <div class="col-md-5">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Date</div>
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <button wire:click="changeDate('decrement')" type="button" class="btn btn-primary">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <input class="form-control" value="{{ $date ?? date('Y-m-d') }}" id="datepicker" style="border-radius: 0;">
                        <button wire:click="changeDate('increment')" type="button" class="btn btn-primary">
                            <i class="fas fa-chevron-right"></i>
                        </button>
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
                                {{ $branch->name }}
                            </option>
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

<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>Branch Name</th>
                <th>Supplier Name</th>
                <th>Product Name</th>
                <th>Sold</th>
            </tr>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->model }}</td>
                    <td>{{ $this->getSoldItemCount($product->id) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No products found with zero quantity.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $products->links() }}
    </div>
</div>
</div>
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
            size: portrait; /* Set to portrait mode */
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
        .receipt-table th, td{
            padding:1px;
        }
        /* Apply a scaling factor to fit the table within the page */
        body {
            transform: scale(1); /* Ensure content is not scaled down */
            transform-origin: top left; /* Ensure scaling starts from top-left corner */
        }
    }
</style>
@endassets

<x-slot:print>
    <div class="printable">
        <table>
            <tr>
                <td style="width: 38%">
                    <img src="{{ asset('assets/img/left_logo.png') }}" alt="" style="width:350px">
                </td>
                <td align="center">
                    <h4><strong>SOLD OUT PRODUCTS REPORT</strong></h4>
                    <h4>
                        <strong>{{ $branch_name ?? 'All Branches' }} - {{ date('F j, Y', strtotime($date)) }}</strong>
                        <small>Page 1 of 1 Printed {{ date('F j h:iA') }}</small>
                    </h4>
                </td>
            </tr>
        </table>
        <table class="table table-bordered receipt-table">
            <thead>
            <tr class="bg-secondary font-w">
                <th>Branch Name</th>
                <th>Supplier Name</th>
                <th>Product Name</th>
                <th>Opening Count</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($allProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->model }}</td>
                    <td>{{ $product->available }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No products found with available quantity.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-slot>

