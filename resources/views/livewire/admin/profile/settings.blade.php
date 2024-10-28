<div>
    <form wire:submit.prevent="updateInfo">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="current-password" class="form-label">Current Password</label>

                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" id="current-password" wire:model="oldPassword">
                                </div>

                                <div>
                                    @error('oldPassword') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="new-password" class="form-label">New Password</label>

                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" id="new-password" wire:model="password">
                                </div>
                                <div>
                                    @error('password') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="confirm-new-password" class="form-label">Confirm New Password</label>

                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" id="confirm-new-password" wire:model="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 mb-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary form-save-button">Update Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
