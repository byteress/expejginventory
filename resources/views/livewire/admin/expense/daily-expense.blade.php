<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Branch List</strong></h1>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
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
        <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
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
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="expense_type">Expense Type</label>
                                <select wire:model="expense" class="form-control" id="expense_type">
                                    <option value="">Select expense</option>
                                  @foreach($expenses as $expense)
                                      <option value="{{ $expense->value }}">{{ $expense->displayName() }}</option>
                                  @endforeach
                                </select>
                                @error('expense')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input wire:model="amount" step="0.01" type="number" min="0.01" class="form-control" id="supplier_code" wire:model="amount"
                                    placeholder="Amount">
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pvc">PVC#</label>
                                <input wire:model="pvc" type="text" class="form-control" id="pvc" placeholder="PVC#">
                                @error('pvc')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                        <div class ="col-md-8">
                            <div class="form-group">
                                <label for="details">Description</label>
                                <textarea wire:model="description" class="form-control" id="details" rows="3" style = "resize:none;"></textarea>
                                @error('username')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="text">Save</span>
                            </button>
                        </div>
                    </div>
                        </form>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                  <tr>
                                    <th scope="col">PVC#</th>
                                    <th scope="col">Expense</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Description</th>
                                      @hasrole('admin')
                                      <th scope="col">Branch</th>
                                      @endhasrole
                                    <th scope="col">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <livewire:admin.expense.partials.expense-item
                                            :key="$item->id"
                                            :expense-id="$item->id"
                                            :expense="$item->expense"
                                            :amount="$item->amount"
                                            :pvc="$item->voucher_number"
                                            :description="$item->description"
                                            :branch-id="$item->branch_id"
                                        >
                                    @empty
                                        <tr>
                                            <td colspan="10" align="center">No items found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                              </table>
                        </div>
                    </div>
                    </div>
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
    });

    $wire.on('date-changed', (event) => {
        $('#datepicker').datepicker('setDate', $wire.get('date'));
    });
</script>
@endscript
