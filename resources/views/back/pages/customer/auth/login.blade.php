@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Customer Login')
@section('content')
    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center" style="color:#e27201;">Customer Login</h2>
        </div>
        <form action="{{ route('customer.login-handler') }}" method="POST">
            @csrf
            <x-alert.form-alert />

            <div class="input-group custom">
                <input type="text" class="form-control form-control-lg" placeholder="Email / Username" name="login_id"
                    value="{{ old('login_id') }}">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                </div>
            </div>

            @error('login_id')
                <div class="d-block text-danger" style="margin-top: -25px;margin-bottom: 15px;">
                    {{ $message }}
                </div>
            @enderror

            <div class="input-group custom">
                <input type="password" class="form-control form-control-lg" placeholder="**********" name="password"
                    id="password">
                <div class="input-group-append custom">
                    <span class="input-group-text">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>
            @error('password')
                <div class="d-block text-danger" style="margin-top: -25px;margin-bottom: 15px;">
                    {{ $message }}
                </div>
            @enderror
            <div class="row pb-30">
                <div class="col-6">
                </div>
                <div class="col-6">
                    <div class="forgot-password">
                        <a href="{{ route('customer.forgot-password') }}">Forgot Password</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group mb-0">
                        <input class="btn btn-primary btn-lg btn-block" style="background-color:#e27201; border-color:#e27201" type="submit" value="Sign In">
                        {{-- <a class="btn btn-primary btn-lg btn-block" href="index.html">Sign In</a> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
