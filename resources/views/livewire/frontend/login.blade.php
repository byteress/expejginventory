<div class="container h-100">

    <!-- Outer Row -->
    <div class="row justify-content-center h-100 align-items-center">

        <div class="col-md-6">

            <div class="card o-hidden border-0 shadow-lg mt-10">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-5">
                                <div class = "d-flex justify-content-center">
                                    <img src="{{ asset('assets/img/jg-logo.jpg') }}" alt="" class ="home-logo" style = "width: 150px;">
                                </div>
                                {{-- <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">LOGIN</h1>
                                </div> --}}
                                @if (session('alert'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('alert') }}
                                    </div>
                                @endif
                                <form class="user mt-2" wire:submit="login">
                                    <div class="form-group">
                                        <input wire:model="email" type="email" class="form-control form-control-user"
                                            id="exampleInputEmail" aria-describedby="emailHelp"
                                            placeholder="Enter Email Address...">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input wire:model="password" type="password"
                                            class="form-control form-control-user" id="exampleInputPassword"
                                            placeholder="Password">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember
                                                Me</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
