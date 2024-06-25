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
                    <div class="pb-20 mt-30">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus">#</th>
                                    <th>Nama Venue</th>
                                    <th>Owner</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Dibuat</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($venue as $item)
                                    <tr>
                                        <td class="table-plus">{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->owner->name }}</td>
                                        <td>{{ ucwords(strtolower($item->address)) }}, {{ ucwords(strtolower($item->village->name)) }}, {{ ucwords(strtolower($item->village->district->name)) }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item text-info"
                                                        href="{{ route('admin.venue.show', ['venue' => $item->id]) }}"><i
                                                            class="dw dw-eye"></i>
                                                        View</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-danger">Tidak Ada Venue Disetujui</span>
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
