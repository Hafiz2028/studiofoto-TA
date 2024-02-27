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
                        <a href="{{ route('admin.user.adminList')}}" class="btn btn-primary btn-sm">
                        <i class="ion-arrow-left-a"></i> Back to Admin User List
                        </a>
                    </div>
                </div>
                <hr>
                <form action="{{ route('admin.user.store-admin')}}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    @if (Session::get('success'))
                        <div class="alert alert-success">
                            <strong><i class="dw dw-checked"></i></strong>
                            {!! Session::get('success') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (Session::get('fail'))
                        <div class="alert alert-danger">
                            <strong><i class="dw dw-checked"></i></strong>
                            {!! Session::get('fail') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{ old('name')}}">
                                @error('name')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                                <label for="">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Username" value="{{ old('username')}}">
                                @error('username')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                                <label for="">Phone Number</label>
                                <input type="text" class="form-control" name="handphone" placeholder="Enter Phone Number" value="{{ old('handphone')}}">
                                @error('handphone')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                                <label for="">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter address" value="{{ old('address')}}">
                                @error('address')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                                <label for="">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ old('email')}}">
                                @error('email')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                                <label for="">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter Password" value="{{ old('password')}}">
                                @error('password')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                                <label for="">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" value="{{ old('password_confirmation')}}">
                                @error('password_confirmation')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">ADD USER</button>
                </form>
            </div>
        </div>
    </div>
@endsection
