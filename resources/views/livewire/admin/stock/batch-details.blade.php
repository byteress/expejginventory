<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Batch Receive History</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <button class="btn btn-secondary" onclick="window.print()">Print</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Model</th>
                            <th>Description</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->sku_number }}</td>
                                <td>{{ $item->model }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No data found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>

<x-slot:print>
    <div class="printable">
    <div class="page">
        <div class="header">
            <h2>RSR#{{ $batch->batch_number }}</h2>
            <p>Branch: {{ $batch->branch->name  }}<br>
                Requested: {{ $batch->requested_by  }} ({{ date('F j, Y', strtotime($batch->created_at)) }})<br>
                Approved: {{ $batch->approvedBy->first_name . ' '. $batch->approvedBy->last_name }} ({{ date('F j, Y', strtotime($batch->created_at)) }})</p>
            <p>Notes: {{ $batch->notes }}</p>
        </div>
        <div class="content">
            <ul>
                @foreach($items as $item)
                <li>{{ $item->quantity }}&nbsp;&nbsp;&nbsp;&nbsp; {{ $item->model }} {{ $item->description }}</li>
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
            <h2>RSR#{{ $batch->batch_number }}</h2>
            <p>Branch: {{ $batch->branch->name  }}<br>
                Requested: {{ $batch->requested_by  }} ({{ date('F j, Y', strtotime($batch->created_at)) }})<br>
                Approved: {{ $batch->approvedBy->first_name . ' '. $batch->approvedBy->last_name }} ({{ date('F j, Y', strtotime($batch->created_at)) }})</p>
            <p>Notes: {{ $batch->notes }}</p>
        </div>
        <div class="content">
            <ul>
                @foreach($items as $item)
                    <li>{{ $item->quantity }} &nbsp;&nbsp;&nbsp;&nbsp;{{ $item->model }} {{ $item->description }}</li>
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
            <h2>RSR#{{ $batch->batch_number }}</h2>
            <p>Branch: {{ $batch->branch->name  }}<br>
                Requested: {{ $batch->requested_by  }} ({{ date('F j, Y', strtotime($batch->created_at)) }})<br>
                Approved: {{ $batch->approvedBy->first_name . ' '. $batch->approvedBy->last_name }} ({{ date('F j, Y', strtotime($batch->created_at)) }})</p>
            <p>Notes: {{ $batch->notes }}</p>
        </div>
        <div class="content">
            <ul>
                @foreach($items as $item)
                    <li>{{ $item->quantity }} &nbsp;&nbsp;&nbsp;&nbsp;{{ $item->model }} {{ $item->description }}</li>
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
