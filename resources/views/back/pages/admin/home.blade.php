@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Admin Home')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Welcome, {{ ucwords(strtolower($admin->name)) }} </h4>
                </div>
            </div>
        </div>
    </div>
    <h5 class="my-4">Venue</h5>
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $totalVenues }}</div>
                        <div class="font-14 text-white weight-500">
                            Total Venue Terdaftar
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-primary">
                            <i class="icon-copy dw dw-sheet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-success">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $activeVenues }}</div>
                        <div class="font-14 text-white weight-500">
                            Venue Aktif
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-success">
                            <i class="icon-copy dw dw-checked"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-info">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $pendingVenues }}</div>
                        <div class="font-14 text-white weight-500">
                            Venue Belum Dikonfirmasi
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-info">
                            <i class="icon-copy dw dw-question"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-danger">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $rejectedVenues }}</div>
                        <div class="font-14 text-white weight-500">
                            Venue Ditolak
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-danger">
                            <i class="icon-copy dw dw-cancel"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h5 class="mb-4">Customer</h5>
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $totalCust }}</div>
                        <div class="font-14 text-white weight-500">
                            Total Customer
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-primary">
                            <i class="icon-copy dw dw-checked"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h5 class="mb-4">Owner</h5>
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $totalOwner }}</div>
                        <div class="font-14 text-white weight-500">
                            Total Owner
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-primary">
                            <i class="icon-copy dw dw-question"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-success">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $activeOwner }}</div>
                        <div class="font-14 text-white weight-500">
                            Owner Aktif
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-success">
                            <i class="icon-copy dw dw-cancel"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
