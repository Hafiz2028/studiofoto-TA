@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page title here')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-dark">Edit Service</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('admin.service.venueServiceList')}}" class="btn btn-primary btn-sm">
                        <i class="ion-arrow-left-a"></i> Back to Venue's Services
                        </a>
                    </div>
                </div>
                <hr>
                <form action="{{ route('admin.service.update-service')}}" method="POST" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="service_id" value="{{ Request('id')}}">
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
                                <label for="">Service Name</label>
                                        <input type="text" class="form-control" name="service_name" placeholder="Enter Service Name" value="{{ $service->service_name}}">
                                @error('service_name')
                                    <span class="text-danger ml-2">
                                        {{ $message}}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">UPDATE</button>
                </form>
            </div>
        </div>
    </div>
@endsection
