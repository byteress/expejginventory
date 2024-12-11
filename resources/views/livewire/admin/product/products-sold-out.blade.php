<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Sold out Products</strong></h1>
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
                    <th>Branch Name</th>
                    <th>Supplier Name</th>
                    <th>Product Name</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name  }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->model }}</td>
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

