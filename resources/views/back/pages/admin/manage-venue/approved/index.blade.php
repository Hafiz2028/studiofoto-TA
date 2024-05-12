@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Approval Venue')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Approved Venue</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Approved Venue
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="h4 text">List Approved Venue</h4>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-borderless table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Nama Venue</th>
                                    <th>Owner</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="sortable_services">
                                @forelse ($venue as $item)
                                    @if ($item->status == 1)
                                        <tr data-index="{{ $item->id }}" data-ordering="">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->owner->name }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <div class="table-actions">
                                                    <form
                                                        action="{{ route('admin.venue.show', ['venue' => $item->id]) }}"
                                                        method="GET">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-info mr-2"
                                                            data-toggle="tooltip" title="Informasi Venue">
                                                            <i class="fa fa-info"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-danger">No Venues found!</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
