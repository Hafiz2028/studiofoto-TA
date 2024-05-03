@stack('stylesheets')
@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : "Add Venue")
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Add Venue</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home')}}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.venue.index')}}">Venue's Manage</a>
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
