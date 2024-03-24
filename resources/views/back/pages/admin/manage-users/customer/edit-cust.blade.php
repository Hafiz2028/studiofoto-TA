@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page title here')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-dark">Edit Customer User</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('admin.user.customer.index') }}" class="btn btn-primary btn-sm">
                            <i class="ion-arrow-left-a"></i> Back to Customer User List
                        </a>
                    </div>
                </div>
                <hr>
                <form action="{{ route('admin.user.customer.update',$customer->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="customer_id" value="{{ Request('id')}}">
                    @csrf
                    @method('PUT')
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
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Name"
                                            value="{{ $customer->name }}"> @error('name')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="">Username</label>
                                        <input type="text" class="form-control" name="username"
                                            placeholder="Username" value="{{ $customer->username }}">
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
                                    value="{{ $customer->address }}">
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
                                            value="{{ $customer->email }}" disabled>
                                        @error('email')
                                            <span class="text-danger ml-2">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="">Phone Number</label>
                                        <input type="text" class="form-control" name="handphone"
                                            placeholder="Phone Number" value="{{ $customer->handphone }}">
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
                                        <button type="submit" class="btn btn-primary float-right">Update</button>
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
