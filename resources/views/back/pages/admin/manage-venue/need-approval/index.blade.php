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
                                    @if ($item->status == 0)
                                        <tr data-index="{{ $item->id }}" data-ordering="">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->owner->name }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <div class="table-actions">
                                                    <form
                                                        action="{{ route('admin.venue.detail-venue', ['id' => $item->id]) }}"
                                                        method="GET">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-info mr-2"
                                                            data-toggle="tooltip" title="Informasi Venue">
                                                            <i class="fa fa-info"></i>
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-outline-success mr-2" data-toggle="modal"
                                                        data-target="#acceptModal{{ $item->id }}">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        data-toggle="modal" data-target="#rejectModal{{ $item->id }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    {{-- Acc Modal --}}
                                                    <div class="modal fade" id="acceptModal{{ $item->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="acceptModalLabel{{ $item->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title text-white"
                                                                        id="acceptModalLabel{{ $item->id }}">
                                                                        Approve Venue</h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
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
                                                                        <button type="submit"
                                                                            class="btn btn-outline-success">
                                                                            Approve
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- Reject Modal --}}
                                                    <div class="modal fade" id="rejectModal{{ $item->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="rejectModalLabel{{ $item->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title text-white"
                                                                        id="rejectModalLabel{{ $item->id }}">
                                                                        Reject Venue</h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('admin.venue.reject-venue', ['id' => $item->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="modal-body">
                                                                        <p>Apakah anda yakin untuk Menolak Venue
                                                                            <b>{{ $item->name }}</b> dari Owner
                                                                            <b>{{ $item->owner->name }}</b> ini?
                                                                        </p>
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="rejectReason{{ $item->id }}">Berikan
                                                                                alasan Venue Ditolak:</label>
                                                                            <textarea id="rejectReason{{ $item->id }}" name="reject_note" class="form-control" rows="3" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit"
                                                                            class="btn btn-outline-danger">Reject</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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
@push('scripts')
@endpush
