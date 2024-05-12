@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Venue')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Edit Venue</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home')}}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.venue.index')}}">Venue's Manage</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.venue.show',$venue->id)}}">Detail Venue</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit Venue
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="pd-20 card-box mb-30">
        <br>
        @livewire('venue.add-venue-tabs', ['venue' => $venue])
    </div>






@endsection

@stack('scripts')
