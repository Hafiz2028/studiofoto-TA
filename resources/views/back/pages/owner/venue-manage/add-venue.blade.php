@stack('stylesheets')
<script src="https://cdn.maptiler.com/maptiler-geocoding-control/v0.0.98/openlayers.umd.js"></script>
<style>
    #map {
        height: 400px;
        width: 70%;
    }
</style>
<style>
    .custom-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    .custom-switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #007bff;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #007bff;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(20px);
        -ms-transform: translateX(20px);
        transform: translateX(20px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 20px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>


@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : "Venue's Manage")
@section('content')

    <div class="mobile-menu-overlay"></div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Add Venue</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            {{-- <a href="{{ route('admin.home') }}">Home</a> --}}
                            <a href="">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Add Venue
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    {{-- real --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <h4 class="text-blue h4">Add New Venue</h4>
        </div>
        <br>
        @livewire('venue.add-venue-tabs')


    </div>
@endsection

@stack('scripts')
