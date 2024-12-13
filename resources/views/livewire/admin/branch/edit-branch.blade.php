<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>{{ $title }}</strong></h1>
                </div>
            </div>

        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a wire:navigate href="{{ route('admin.branch') }}" type ="button" class="btn btn-secondary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="text">Back</span>
            </a>
        </div>
        <div class="card-body">
            <form wire:submit="submit">
            <div class="container">
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
                                <label for="form-input-1">Branch Name</label>
                                <input wire:model="name" type="text" class="form-control" id="form-input-1"
                                    placeholder="">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="form-input-1">Branch Address</label>
                                <input wire:model="address" type="text" class="form-control" id="form-input-1"
                                    placeholder="">
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="form-input-1">Branch Phone</label>
                                <input wire:model="phone" type="text" class="form-control" id="form-input-1"
                                    placeholder="">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Branch Description</label>
                                <textarea wire:model="description" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <button type ="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Update</span>
                            </button>
                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    </form>
</div>
