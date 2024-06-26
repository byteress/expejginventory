<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-7">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            <h4 class="mt-3"><strong>Stephan Sutter</strong> <span class="badge badge-success">Active</span></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-2 br-g">
                                <h6>Total Orders</h6>
                                <h6><b><u>20</u></b></h6>
                            </div>
                            <div class="col-md-2 br-g">
                                <h6>Last Payment</h6>
                                <h6><b><u>Aug 25 2024</u></b></h6>
                            </div>
                            <div class="col-md-2">
                                <h6 class ="ml-3">Next Payment</h6>
                                <h6 class ="ml-3"><b><u>Sept 31 2024</u></b></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="upcoming-tab" data-toggle="tab" data-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming Balance</button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="payment-history-tab" data-toggle="tab" data-target="#payment-history" type="button" role="tab" aria-controls="payment-history" aria-selected="false">Payment History</button>
                            </li>
                          </ul>
                          <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h4 class="mt-3 ml-1"><strong>Order #00000001</strong> - Upcoming Balance</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex justify-content-end">
                                            <div class="btn-group mt-3 ">
                                                <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                    Penalty : <b>₱250.00</b>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button class="dropdown-item" type="button">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Receipt Number</span>
                                                </div>
                                                <input type="text" class="form-control" wire:model="receiptNumber" placeholder="Receipt Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            <h4 class="ml-1">Balance : <strong>₱3000.00</strong></h4>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive mt-4">
                                            <table class="table table-bordered" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Option</th>
                                                        <th>Amount</th>
                                                        <th>Due Date</th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="input-group" style = "width:350px;">
                                                                <div class="input-group-prepend">
                                                                    <label class="input-group-text"
                                                                        for="inputGroupSelect01">Option</label>
                                                                </div>
                                                                <select class="custom-select" id="inputGroupSelect01">
                                                                    <option disabled selected>Select Payment Method</option>
                                                                    <option value="Cash">Cash</option>
                                                                    <option value="Gcash">Gcash</option>
                                                                    <option value="Paymaya">Paymaya</option>
                                                                    <option value="Bank Transfer">Bank Transfer</option>
                                                                    <option value="Others">Others</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group" style = "width:200px;">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Php</span>
                                                                </div>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h6 class ="mt-2"><b><u>Aug 25 2024</u></b></h6>
                                                        </td>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="payment-history" role="tabpanel" aria-labelledby="payment-history-tab">

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Receipt #</th>
                                                <th>Option</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Payment Date</th>
                                                <th>Penalty</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class ="mt-2"><b>R-0303043</b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b>Cash</b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b>₱3000.00</b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b><u>Aug 25 2024</u></b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b><u>Aug 27 2024</u></b></h6>
                                                </td>
                                                <td>
                                                    <h5 class ="mt-1"><span class="badge badge-danger">200.00</span></h5>
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                          </div>
                    </div>
                </div>
                {{-- Upcoming end --}}

                {{-- Customer Details --}}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h4 class="mt-3 ml-1"><strong>Customer Information</strong></h4>
                        <hr>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip01">First name</label>
                                <input disabled type="text" class="form-control" id="validationTooltip01"
                                    value="Test" required>
                                <div class="valid-tooltip">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip02">Last name</label>
                                <input disabled type="text" class="form-control" id="validationTooltip02"
                                    value="Test" required>
                                <div class="valid-tooltip">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip03">Phone Number</label>
                                <input disabled type="text" class="form-control" id="validationTooltip03"
                                    value="Test">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip03">Email Address</label>
                                <input disabled type="text" class="form-control" id="validationTooltip03"
                                    value="Test">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip05">Address</label>
                                <textarea disabled class="form-control" id="validationTooltip05">Test</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Customer Details End --}}
            </div>
            <div class="col-md-5">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h4 class="mt-3 ml-1">Total Balance : <strong>₱65325.00</strong></h4>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body upcoming-section">

                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                <h5 class="mt-3 ml-1"><strong>Order #00000001</strong> - Installment </h5>
                                <h5 class="mt-3 ml-1">Balance : <strong>₱32440.00</strong></h5>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Interest(%)</th>
                                                <th>Due Date</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class ="mt-2"><b>₱3000.00</b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b>₱150.00(5%)</b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b><u>Aug 25 2024</u></b></h6>
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
