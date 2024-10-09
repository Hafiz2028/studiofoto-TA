@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page title here')
@section('content')
    <div class="login-box bg-white box-shadow vorder-radius-10">
        <div class="login-title">
            <h2 class="text-center" style="color:#e27201;">Register Customer Account</h2>
        </div>
        <form action="{{ route('customer.create') }}" method="POST">
            @csrf
            <x-alert.form-alert />


            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Name..."
                    value="{{ old('name') }}">
                @error('name')
                    <span class="text-danger ml-2">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Enter Username..."
                    value="{{ old('username') }}">
                @error('username')
                    <span class="text-danger ml-2">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter Email..."
                    value="{{ old('email') }}">
                @error('email')
                    <span class="text-danger ml-2">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">No HP</label>
                <input type="number" class="form-control" name="handphone" placeholder="Enter Phone Number..."
                    value="{{ old('handphone') }}">
                @error('handphone')
                    <span class="text-danger ml-2">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter Password..."
                    value="{{ old('password') }}" id="show_hide_password">
                @error('password')
                    <span class="text-danger ml-2">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password"
                    value="{{ old('password_confirmation') }}">
                @error('password_confirmation')
                    <span class="text-danger ml-2">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group mb-0">
                        <button type="submit" class="btn btn-primary btn-lg btn-block"
                            style="background-color:#e27201; border-color:#e27201">Create Account</button>
                    </div> 
                    <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373"
                        style="color:rgb(112,115,115)">OR</div>
                    <div class="input-group mb-0">
                        <a href="{{ route('customer.login') }}"
                            class="btn btn-outline-primary btn-lg btn-block custom-btn-outline">Sign In</a>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <style>
        .custom-btn-outline {
            color: #e27201;
            border-color: #e27201;
        }

        .custom-btn-outline:hover {
            color: #fff;
            background-color: #e27201;
            border-color: #e27201;
        }
    </style>
@endsection
