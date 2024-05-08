@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page title here')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-dark">Add Admin User</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('admin.user.adminList') }}" class="btn btn-primary btn-sm">
                            <i class="ion-arrow-left-a"></i> Back to Admin User List
                        </a>
                    </div>
                </div>
                <hr>
                <form action="{{ route('admin.user.store-admin') }}" method="POST" enctype="multipart/form-data"
                    class="mt-3">
                    @csrf
                    <x-alert.form-alert/>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Name"
                                            value="{{ old('name') }}"> @error('name')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="">Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Username"
                                            value="{{ old('username') }}">
                                        @error('username')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Address"
                                    value="{{ old('address') }}">
                                @error('address')
                                    <span class="text-danger ml-2">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <label for="">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Email"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="">Phone Number</label>
                                        <input type="text" class="form-control" name="handphone"
                                            placeholder="Phone Number" value="{{ old('handphone') }}">
                                        @error('handphone')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <label for="">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Password"
                                            value="{{ old('password') }}" id="show_hide_password">
                                        @error('password')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            placeholder="Confirm Password" value="{{ old('password_confirmation') }}">
                                        @error('password_confirmation')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary float-right">ADD USER</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
