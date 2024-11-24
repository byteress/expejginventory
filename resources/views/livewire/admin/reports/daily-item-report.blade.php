<div>
    <div class="container-fluid no-print">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 mb-2 text-primary admin-title"><strong>Daily Item Reports</strong></h1>
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

    <div class="card mt-2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>SKU</th>
                        <th>Model</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($history as $item)
                        <tr>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->sku_number }}</td>
                            <td>{{ $item->model }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                                <button class="btn btn-primary" onclick="window.location.href = '{{ url('admin/reports/daily-items/'. $item->branch_id .'/' . $item->id) }}'">Print</button>
                            </td>
                        </tr>
                    @endforeach

                    @if($history->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No items found.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            {{ $history->links() }}
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
