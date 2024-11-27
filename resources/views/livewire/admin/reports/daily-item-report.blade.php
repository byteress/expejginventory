<div>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 mb-2 text-primary admin-title"><strong>Daily Items Reports</strong></h1>
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
                            <th></th>
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
                            <td></td>
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
</div>

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
