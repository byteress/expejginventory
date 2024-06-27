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
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-9">--}}
{{--                                        <h4 class="mt-3 ml-1"><strong>Order #00000001</strong> - Upcoming Balance</h4>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-3">--}}
{{--                                        <div class="d-flex justify-content-end">--}}
{{--                                            <div class="btn-group mt-3 ">--}}
{{--                                                <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">--}}
{{--                                                    Penalty : <b>₱250.00</b>--}}
{{--                                                </button>--}}
{{--                                                <div class="dropdown-menu">--}}
{{--                                                    <button class="dropdown-item" type="button">Remove</button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <hr>
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
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Receipt Number</span>
                                                </div>
                                                <input type="text" class="form-control" wire:model="receiptNumber" placeholder="Receipt Number">
                                            </div>
                                            @error('receiptNumber')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="d-flex justify-content-end mt-1">
                                                <button wire:click="newPaymentMethod"
                                                        class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-plus"></i>
                                                </span>
                                                    <span class="text">New Payment Method</span>
                                                </button>
                                            </div>
                                    </div>
                                </div>
                              <div class="col-md-12 mt-2">
                                  <div class="card shadow d-none d-md-block">
                                      <div class="card-body">
                                          <div class="row">
                                              <div class="col-md-4">
                                                  <p class="text-primary mb-0">Payment Type</p>
                                              </div>
                                              <div class="col-md-3 text-center">
                                                  <p class="text-primary mb-0">Reference #</p>
                                              </div>
                                              <div class="col-md-3 text-center">
                                                  <p class="text-primary mb-0">Amount</p>
                                              </div>
                                              <div class="col-md-2 text-center">
                                                  <p class="text-primary mb-0">Actions</p>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-12 mt-2 mb-3">
                                  <div class="card shadow mb-2">
                                      <div class="card-body">
                                          @foreach ($amounts as $amount)
                                              <div class="row" wire:key="payment-methods-{{ $loop->index }}">
                                                  <div class="col-md-4 mt-1">
                                                      <div class="input-group">
                                                          <div class="input-group-prepend">
                                                              <label class="input-group-text"
                                                                     for="inputGroupSelect01">Type</label>
                                                          </div>
                                                          <select class="custom-select" id="inputGroupSelect01"
                                                          wire:model="paymentMethods.{{ $loop->index }}">
                                                              <option disabled selected>Select Payment Method</option>
                                                              <option value="Cash">Cash</option>
                                                              <option value="Gcash">Gcash</option>
                                                              <option value="Paymaya">Paymaya</option>
                                                              <option value="Bank Transfer">Bank Transfer</option>
                                                              <option value="Others">Others</option>
                                                          </select>
                                                      </div>
                                                      @error('paymentMethods.' . $loop->index)
                                                      <span class="text-danger">{{ $message }}</span>
                                                      @enderror
                                                  </div>
                                                  <div class="col-md-3 mt-1">
                                                      <div class="d-flex justify-content-center">
                                                          <div class="input-group">
                                                              <div class="input-group-prepend">
                                                                  <span class="input-group-text">#</span>
                                                              </div>
                                                              <input type="text" class="form-control"
                                                              wire:model="referenceNumbers.{{ $loop->index }}">
                                                          </div>
                                                      </div>
                                                      @error('referenceNumbers.' . $loop->index)
                                                      <span class="text-danger">{{ $message }}</span>
                                                      @enderror
                                                  </div>
                                                  <div class="col-md-3 mt-1">
                                                      <div class="d-flex justify-content-center">
                                                          <div class="input-group">
                                                              <div class="input-group-prepend">
                                                                  <span class="input-group-text">Php</span>
                                                              </div>
                                                              <input type="number" class="form-control"
                                                              wire:model.live="amounts.{{ $loop->index }}">
                                                          </div>
                                                      </div>
                                                      @error('amounts.' . $loop->index)
                                                      <span class="text-danger">{{ $message }}</span>
                                                      @enderror
                                                  </div>
                                                  @if (!$loop->first)
                                                      <div class="col-md-2">
                                                          <div class="d-flex justify-content-center">
                                                              <button
                                                                  wire:click="removePaymentMethod({{ $loop->index }})"
                                                                  class="btn btn-danger btn-sm mt-1">
                                                                  <i class="fas fa-trash-alt"></i>
                                                              </button>
                                                          </div>
                                                      </div>
                                                  @endif
                                              </div>
                                          @endforeach
                                      </div>
                                  </div>
                              </div>
                                <div class="d-flex justify-content-end mt-1">
                                    <button wire:click="submitPayment" class="btn btn-primary btn-icon-split">
                                        <span class="text">Submit Payment</span>
                                    </button>
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
                                    value="{{ $customer->first_name }}" required>
                                <div class="valid-tooltip">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip02">Last name</label>
                                <input disabled type="text" class="form-control" id="validationTooltip02"
                                    value="{{ $customer->last_name }}" required>
                                <div class="valid-tooltip">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip03">Phone Number</label>
                                <input disabled type="text" class="form-control" id="validationTooltip03"
                                    value="{{ $customer->phone }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip03">Email Address</label>
                                <input disabled type="text" class="form-control" id="validationTooltip03"
                                    value="{{ $customer->email }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationTooltip05">Address</label>
                                <textarea disabled class="form-control" id="validationTooltip05">{{ $customer->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Customer Details End --}}
            </div>
            <div class="col-md-5">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h4 class="mt-3 ml-1">Total Balance : <strong>@money($balance)</strong></h4>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body upcoming-section">
                        @foreach($installment_bills as $bills)
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                <h5 class="mt-3 ml-1"><strong>Order #{{ str_pad((string) $bills->id, 12, '0', STR_PAD_LEFT) }}</strong> - Installment </h5>
{{--                                <h5 class="mt-3 ml-1">Balance : <strong>₱32440.00</strong></h5>--}}
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Penalty</th>
                                                <th>Due Date</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class ="mt-2"><b>@money($bills->balance)</b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b>@money($bills->penalty)</b></h6>
                                                </td>
                                                <td>
                                                    <h6 class ="mt-2"><b><u>{{ date('F j, Y', strtotime($bills->due)) }}</u></b></h6>
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
