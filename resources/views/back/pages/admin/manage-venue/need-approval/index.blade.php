@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Need Approval Venue')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Need Approval Venue</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Need Approval Venue
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
                            <h4 class="h4 text">List Need Approval Venue</h4>
                        </div>
                    </div>

                    <div class="pd-20 card-box mb-30">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h4 class="h4 text">List Rejected Venue</h4>
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
                                            <td>{{ $item->address }}</td>
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
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <span class="text-danger">Tidak Ada belum dikonfirmasi</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                    {{-- Acc Modal --}}
                                    <div class="modal fade" id="acceptModal{{ $item->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="acceptModalLabel{{ $item->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title text-white"
                                                        id="acceptModalLabel{{ $item->id }}">
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
                                    <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="rejectModalLabel{{ $item->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title text-white"
                                                        id="rejectModalLabel{{ $item->id }}">
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
                                                        <button type="submit"
                                                            class="btn btn-outline-danger">Reject</button>
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
        </div>
    </div>
@endsection
@push('scripts')
@endpush
