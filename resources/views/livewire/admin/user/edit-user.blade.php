<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit {{ $firstName }} {{ $lastName }}</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a wire:navigate href="{{ route('admin.users') }}" role="button" class="btn btn-secondary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="text">Back</span>
            </a>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="submit">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" wire:model="firstName" placeholder="First Name">
                                @error('firstName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" wire:model="lastName" placeholder="Last Name">
                                @error('lastName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" wire:model="email" placeholder="Email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="phone" class="form-control" id="phone" wire:model="phone" placeholder="Phone">
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="address" class="form-control" id="address" wire:model="address" placeholder="Address">
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" wire:model="role">
                                    <option value="" disabled>Select Role</option>
                                    @foreach (\IdentityAndAccessContracts\Enums\Role::cases() as $role)
                                        <option value="{{ $role->value }}">{{ $role->displayName() }}</option>
                                    @endforeach
                                    <!-- Add more user types if needed -->
                                </select>
                                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Branch</label>
                                <select class="form-control" id="role" wire:model="branch">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                    <!-- Add more user types if needed -->
                                </select>
                                @error('branch') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-primary btn-icon-split">
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
</div>