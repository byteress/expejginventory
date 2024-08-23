<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h1 class="h3 mb-2 text-gray-800">Daily Expense</h1>
        </div>
      </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Filter</div>
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-chevron-left"></i></button>

                            <input class = "form-control" value="" id="datepicker" style ="border-radius: 0;">

                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="submit">
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
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="expense_type">Expense Type</label>
                                <select class="form-control" id="expense_type">
                                  <option>1</option>
                                  <option>2</option>
                                  <option>3</option>
                                  <option>4</option>
                                  <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="supplier_code" wire:model="code"
                                    placeholder="Amount">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                  <tr>
                                    <th scope="col">Item #</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <th><span>Lorem Ipsum</span> <input type="text" class = "form-control" style = "display:none;"></th>
                                    <td><span>$400.00</span> <input type="text" class = "form-control" style = "display:none;"></td>
                                    <td>
                                        <a href="#" class="btn btn-success btn-circle btn-sm ml-2">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-circle btn-sm ml-2">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th><span style = "display:none;">Lorem Ipsum</span> <input type="text" class = "form-control" value = "Lorem Ipsum"></th>
                                    <td><span style = "display:none;">$400.00</span> <input type="text" class = "form-control" value = "$400.00"></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm ml-2">
                                            Save
                                        </a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="text">Save</span>
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
            </form>
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
</script>
@endscript
