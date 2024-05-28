<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>SKU</th>
                                <th>Model</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>On Hand</th>
                                <th>Transfer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $data = json_decode($details->data);
                            @endphp
                            @forelse ($data->products as $key => $value)
                                <tr wire:key="{{ $key }}">
                                    <td>{{ $products[$key]->supplier->code }}</td>
                                    <td>{{ $products[$key]->sku_number }}</td>
                                    <td>{{ $products[$key]->model }}</td>
                                    <td>{{ $products[$key]->description }}</td>
                                    <td>{{ $value->filled ?? 0 }}/{{ $value->quantity }}</td>
                                    <td><livewire:admin.stock.partial.on-hand :key="$key . '-' . ($value->filled ?? 0)"
                                            productId="{{ $key }}"
                                            branchId="{{ auth()->user()->hasRole('admin') ? null : auth()->user()->branch_id }}">
                                    </td>
                                    <td>
                                        <form wire:submit="transfer('{{ $key }}')">
                                            <div class="input-group">
                                                @if(isset($branch) && isset($data->contributions->$branch->$key))
                                                <input type="number" required min="1" class="form-control" value="{{ $data->contributions->$branch->$key }}"
                                                    placeholder="{{ $data->contributions->$branch->$key }}" disabled
                                                    aria-describedby="basic-addon2">
                                                @else
                                                <input wire:model="quantityToTransfer.{{ $key }}" type="number" required min="1" class="form-control"
                                                    placeholder="Quantity to transfer" aria-label="Quantity to transfer"
                                                    aria-describedby="basic-addon2">
                                                @endif
                                                <div class="input-group-append">
                                                    @if(isset($branch) && isset($data->contributions->$branch->$key))
                                                        <button class="btn btn-danger" type="button">Cancel</button>
                                                    @else
                                                        <button class="btn btn-primary" type="submit">Transfer</button>
                                                    @endif
                                                </div>
                                            </div>
                                            @error("quantityToTransfer.$key") <span class="text-danger">{{ $message }}</span> @enderror
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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


    </div>
    <!-- /.container-fluid -->

</div>
