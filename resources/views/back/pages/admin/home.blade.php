@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Admin Home')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Welcome, {{ ucwords(strtolower($user->name)) }} </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $totalVenues }}</div>
                        <div class="font-14 text-white weight-500">
                            Venue Terdaftar
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
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $totalCust }}</div>
                        <div class="font-14 text-white weight-500">
                            Customer
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

        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $totalOwner }}</div>
                        <div class="font-14 text-white weight-500">
                            Owner
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
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text">Venue Belum dikonfirmasi</h4>
                    </div>
                </div>
                <div class="pb-20 mt-10">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus">#</th>
                                <th>Nama Venue</th>
                                <th>Owner</th>
                                <th>Alamat</th>
                                <th>Tanggal Dibuat</th>
                                {{-- <th class="datatable-nosort">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($venue as $item)
                                <tr>
                                    <td class="table-plus">{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->owner->user->name }}</td>
                                    <td>{{ ucwords(strtolower($item->address)) }},
                                        {{ ucwords(strtolower($item->village->name)) }},
                                        {{ ucwords(strtolower($item->village->district->name)) }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    {{-- <td>
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
                                                <a class="dropdown-item text-success" data-toggle="modal"
                                                    data-target="#acceptModal{{ $item->id }}">
                                                    <i class="icon-copy dw dw-checked"></i> Accept
                                                </a>
                                                <a class="dropdown-item text-danger" data-toggle="modal"
                                                    data-target="#rejectModal{{ $item->id }}">
                                                    <i class="icon-copy dw dw-cancel"></i> Reject
                                                </a>
                                            </div>
                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <span class="text-danger">Tidak Ada belum dikonfirmasi</span>
                                    </td>
                                </tr>
                            @endforelse
                            {{-- Acc Modal --}}
                            <div class="modal fade" id="acceptModal{{ $item->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="acceptModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title text-white" id="acceptModalLabel{{ $item->id }}">
                                                Approve Venue</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah anda yakin untuk Approve Venue
                                            <b>{{ $item->name }}</b> dari Owner
                                            <b>{{ $item->owner->name }}</b> ini?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger"
                                                data-dismiss="modal">Cancel</button>
                                            <form id="approve-form"
                                                action="{{ route('admin.venue.approve-venue', ['id' => $item->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-success">
                                                    Approve
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Reject Modal --}}
                            <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="rejectModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title text-white" id="rejectModalLabel{{ $item->id }}">
                                                Reject Venue</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.venue.reject-venue', ['id' => $item->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <p>Apakah anda yakin untuk Menolak Venue
                                                    <b>{{ $item->name }}</b> dari Owner
                                                    <b>{{ $item->owner->name }}</b> ini?
                                                </p>
                                                <div class="form-group">
                                                    <label for="rejectReason{{ $item->id }}">Berikan
                                                        alasan Venue Ditolak:</label>
                                                    <textarea id="rejectReason{{ $item->id }}" name="reject_note" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-outline-danger">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
