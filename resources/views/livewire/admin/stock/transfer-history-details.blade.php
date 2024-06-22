<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Transfer Receive History</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <button class="btn btn-secondary" onclick="window.print()">Print</button>
                    </div>
                </div>

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
                <form wire:submit="submit">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Model</th>
                            <th>Description</th>
                            <th>Transferred</th>
                            <th>Received</th>
                            <th>Damaged</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->sku_number }}</td>
                                <td>{{ $item->model }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->transferred }}</td>
                                @if($transfer->status == 0 && (auth()->user()->branch_id == $transfer->receiver_branch || auth()->user()->hasRole('admin')))
                                    <td><div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"
                                                                      id="basic-addon3">Qty.</span>
                                            </div>
                                            <input
                                                wire:model="quantities.{{ $item->product_id }}.received"
                                                type="number" class="form-control" id="basic-url"
                                                min="0" required aria-describedby="basic-addon3"
                                                style = "width:50px;">
                                        </div>
                                        @error("quantities.$item->product_id.received")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td><div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"
                                                                      id="basic-addon3">Qty.</span>
                                            </div>
                                            <input
                                                wire:model="quantities.{{ $item->product_id }}.damaged"
                                                type="number" class="form-control" id="basic-url"
                                                min="0" required aria-describedby="basic-addon3"
                                                style = "width:50px;">
                                        </div>
                                        @error("quantities.$item->product_id.damaged")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                @else
                                    <td>{{ $item->received  }}</td>
                                    <td>{{ $item->damaged  }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No data found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    @if($transfer->status == 0 && (auth()->user()->branch_id == $transfer->receiver_branch || auth()->user()->hasRole('admin')))
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Complete Transfer</button>
                    </div>
                    @endif
                </div>
                </form>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>

<x-slot:print>
    <div class="printable">
        <div class="page">
            <div class="header">
                <h2>SDR#{{ $transfer->transfer_number }}</h2>
                <p>Source Branch: {{ $transfer->source->name  }}<br>
                    Receiving Branch: {{ $transfer->receiving->name  }}<br>
                    Driver: {{ $transfer->truckDriver->first_name . ' '. $transfer->truckDriver->last_name }}
                    Truck: {{ $transfer->truck  }}<br>
                    Requested: {{ $transfer->requestedBy->first_name . ' '. $transfer->requestedBy->last_name }}
                    ({{ date('F j, Y', strtotime($transfer->created_at)) }})</p>
                <p>Notes: {{ $transfer->notes }}</p>
            </div>
            <div class="content">
                <ul>
                    @foreach($items as $item)
                        <li>{{ $item->transferred }}
                            &nbsp;&nbsp;&nbsp;&nbsp; {{ $item->model }} {{ $item->description }}</li>
                    @endforeach
                </ul>
                <p>----------------none-follows----------</p>
            </div>
            <div class="footer">
                <p>Received by: ____________________<br>
                    Delivered by: ____________________<br>
                    ----Sender----</p>
            </div>
        </div>
        <div class="page">
            <div class="header">
                <h2>SDR#{{ $transfer->transfer_number }}</h2>
                <p>Source Branch: {{ $transfer->source->name  }}<br>
                    Receiving Branch: {{ $transfer->receiving->name  }}<br>
                    Driver: {{ $transfer->truckDriver->first_name . ' '. $transfer->truckDriver->last_name }}
                    Truck: {{ $transfer->truck  }}<br>
                    Requested: {{ $transfer->requestedBy->first_name . ' '. $transfer->requestedBy->last_name }}
                    ({{ date('F j, Y', strtotime($transfer->created_at)) }})</p>
                <p>Notes: {{ $transfer->notes }}</p>
            </div>
            <div class="content">
                <ul>
                    @foreach($items as $item)
                        <li>{{ $item->transferred }}
                            &nbsp;&nbsp;&nbsp;&nbsp; {{ $item->model }} {{ $item->description }}</li>
                    @endforeach
                </ul>
                <p>----------------none-follows----------</p>
            </div>
            <div class="footer">
                <p>Received by: ____________________<br>
                    Delivered by: ____________________<br>
                    ----Receiver----</p>
            </div>
        </div>
        <div class="page">
            <div class="header">
                <h2>SDR#{{ $transfer->transfer_number }}</h2>
                <p>Source Branch: {{ $transfer->source->name  }}<br>
                    Receiving Branch: {{ $transfer->receiving->name  }}<br>
                    Driver: {{ $transfer->truckDriver->first_name . ' '. $transfer->truckDriver->last_name }}
                    Truck: {{ $transfer->truck  }}<br>
                    Requested: {{ $transfer->requestedBy->first_name . ' '. $transfer->requestedBy->last_name }}
                    ({{ date('F j, Y', strtotime($transfer->created_at)) }})</p>
                <p>Notes: {{ $transfer->notes }}</p>
            </div>
            <div class="content">
                <ul>
                    @foreach($items as $item)
                        <li>{{ $item->transferred }}
                            &nbsp;&nbsp;&nbsp;&nbsp; {{ $item->model }} {{ $item->description }}</li>
                    @endforeach
                </ul>
                <p>----------------none-follows----------</p>
            </div>
            <div class="footer">
                <p>Received by: ____________________<br>
                    Delivered by: ____________________<br>
                    ----Inventory----</p>
            </div>
        </div>
    </div>
</x-slot>

@assets
<style>
    .page {
        width: 210mm;
        height: 297mm;
        padding: 20mm;
        margin: 10mm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        page-break-after: always;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    ul {
        list-style-type: none; /* Remove bullets */
        padding: 0; /* Remove padding */
        margin: 0; /* Remove margins */
    }

    .content {
        font-size: 14px;
        flex-grow: 1;
    }

    .footer {
        margin-top: 20px;
        text-align: center;
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
