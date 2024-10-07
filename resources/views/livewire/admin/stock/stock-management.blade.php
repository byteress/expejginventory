<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="h3 text-primary admin-title mb-0"><strong>Manage Stocks</strong></h1>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3 offset-md-9">
                                <div class="form-group">
                                    <input type="text" class="float-left form-control" placeholder="Search..."
                                           wire:model.live="search">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-secondary font-w">
                                    <th>Supplier</th>
                                    <th>SKU</th>
                                    <th>Model</th>
                                    <th>Description</th>
                                    <th>Available</th>
                                    <th>Reserved</th>
                                    <th>Damaged</th>
                                    <th>Sold</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($products as $product)
                                    <tr wire:key="{{ $product->id }}">
                                        <td>{{ $product->code }}</td>
                                        <td>{{ $product->sku_number }}</td>
                                        <td>{{ $product->model }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td><livewire:admin.stock.partial.on-hand :key="now() . '-available-' . $product->id" productId="{{ $product->id }}" branch-id="{{ auth()->user()?->branch_id  }}" />
                                        <td><livewire:admin.stock.partial.on-hand :key="now() . '-reserved-' . $product->id" productId="{{ $product->id }}" branch-id="{{ auth()->user()?->branch_id  }}" type="reserved" />
                                        <td><livewire:admin.stock.partial.on-hand :key="now() . '-damaged-' . $product->id" productId="{{ $product->id }}" branch-id="{{ auth()->user()?->branch_id  }}" type="damaged" />
                                        <td><livewire:admin.stock.partial.on-hand :key="now() . '-sold-' . $product->id" productId="{{ $product->id }}" branch-id="{{ auth()->user()?->branch_id  }}" type="sold" />
                                        <td><livewire:admin.stock.partial.on-hand :key="now() . '-total-' . $product->id" productId="{{ $product->id }}" branch-id="{{ auth()->user()?->branch_id  }}" type="total" />
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#receive-modal-{{ $product->id  }}">Receive</button>
                                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button>
                                                <div class="dropdown-menu">
                                                    <a data-toggle="modal" data-target="#receive-damaged-modal-{{ $product->id  }}" class="dropdown-item" href="#">Receive Damaged</a>
                                                    <a data-toggle="modal" data-target="#set-damaged-modal-{{ $product->id  }}" class="dropdown-item" href="#">Set as Damaged</a>
                                                </div>
                                                <!-- Receive Modal -->
                                                <div wire:ignore.self class="modal fade" id="receive-modal-{{ $product->id  }}" tabindex="-1"
                                                     role="dialog" aria-labelledby="deleteModal1Label" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form wire:submit="receive('{{ $product->id  }}')">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModal1Label">Receive Product</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if (session('alert'))
                                                                        <div class="alert alert-danger" role="alert">
                                                                            {{ session('alert') }}
                                                                        </div>
                                                                    @endif

                                                                    <div class="form-group">
                                                                        <label for="receive_quantity">Quantity</label>
                                                                        <input required type="number" class="form-control" id="receive_quantity" wire:model="quantity" placeholder="Quantity">
                                                                        @error('quantity')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                            <div class="form-group" @unlessrole('admin') style="display:none;" @endunlessrole>
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
                                                                <div class="modal-footer">
                                                                    <button wire:click="cancel" type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Receive Modal -->

                                                <!-- Receive Damaged Modal -->
                                                <div wire:ignore.self class="modal fade" id="receive-damaged-modal-{{ $product->id  }}" tabindex="-1"
                                                     role="dialog" aria-labelledby="deleteModal1Label" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form wire:submit="receiveDamaged('{{ $product->id  }}')">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModal1Label">Receive Damaged Product</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if (session('alert_damaged'))
                                                                        <div class="alert alert-danger" role="alert">
                                                                            {{ session('alert_damaged') }}
                                                                        </div>
                                                                    @endif

                                                                    <div class="form-group">
                                                                        <label for="receive_quantity">Quantity</label>
                                                                        <input required type="number" class="form-control" id="receive_quantity" wire:model="quantity" placeholder="Quantity">
                                                                        @error('quantity')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                        <div class="form-group" @unlessrole('admin') style="display:none;" @endunlessrole>
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
                                                                <div class="modal-footer">
                                                                    <button wire:click="cancel" type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Receive Damaged Modal -->

                                                <!-- Set Damaged Modal -->
                                                <div wire:ignore.self class="modal fade" id="set-damaged-modal-{{ $product->id  }}" tabindex="-1"
                                                     role="dialog" aria-labelledby="deleteModal1Label" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form wire:submit="setDamaged('{{ $product->id  }}')">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModal1Label">Set as Damaged Product</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if (session('alert_set_damaged'))
                                                                        <div class="alert alert-danger" role="alert">
                                                                            {{ session('alert_set_damaged') }}
                                                                        </div>
                                                                    @endif

                                                                    <div class="form-group">
                                                                        <label for="receive_quantity">Quantity</label>
                                                                        <input required type="number" class="form-control" id="receive_quantity" wire:model="quantity" placeholder="Quantity">
                                                                        @error('quantity')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                        <div class="form-group" @unlessrole('admin') style="display:none;" @endunlessrole>
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
                                                                <div class="modal-footer">
                                                                    <button wire:click="cancel" type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Set Damaged Modal -->
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">No products found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>

            </div>
            {{-- Data Table --}}

            {{-- <div class="col-md-6">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('alert'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('alert') }}
                            </div>
                        @endif
                        <form wire:submit="submit">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Supplier</th>
                                            <th>SKU</th>
                                            <th>Model</th>
                                            <th>Description</th>
                                            <th>On Hand</th>
                                            <th>Request</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($selectedProducts as $product)
                                            <tr wire:key="selected-{{ $product->id }}">
                                                <td>{{ $product->code }}</td>
                                                <td>{{ $product->sku_number }}</td>
                                                <td>{{ $product->model }}</td>
                                                <td>{{ $product->description }}</td>
                                                <td><span wire:init="getStocks('{{ $product->id }}')">{!! $stocks[$product->id] ?? '' !!}</span></td>
                                                <td>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon3">Qty.</span>
                                                        </div>
                                                        <input wire:model="quantities.{{ $product->id }}"
                                                            type="number" class="form-control" id="basic-url"
                                                            min="1" required aria-describedby="basic-addon3"
                                                            style = "width:50px;">
                                                    </div>
                                                </td>
                                                <td><button wire:click="remove('{{ $product->id }}')" type="button"
                                                        class="btn btn-danger" data-dismiss="modal">Remove</button></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">No products found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="col-md-4" @unlessrole('admin') style="display:none;" @endunlessrole>
                                    <div class="form-group">
                                        <label for="supplier">Branch</label>
                                        <select class="form-control" id="supplier" wire:model="branch">
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
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div> --}}
        </div>

    </div>
    <!-- /.container-fluid -->

</div>

@script
    <script>
        $wire.on('close-modal', () => {
            $('.modal').modal('hide');
        });
    </script>
@endscript
