@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : "Venue's Manage")
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Venue's Manage</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            {{-- <a href="{{ route('admin.home') }}">Home</a> --}}
                            <a href="">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Venue's Manage
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
                            <h5 class="h4 text">List Venue Owner <strong
                                    style="color: #0011c9;">{{ ucwords(Auth::User()->name) }}</strong></h5>
                        </div>
                        <div class="pull-right">
                            {{-- @if (Auth::user()->owner->ktp) --}}
                            <a style="float:right; margin-right:5px;" href="{{ route('owner.venue.create') }}">
                                <button class="btn btn-primary" type="button">Add Venue</button>
                            </a>
                        </div>
                    </div>
                    <hr>
                    @if ($venues->count() == 0)
                        <div class="row justify-content-around">
                            <div class="card">
                                <div class="card-body">
                                    <h3>
                                        <center>Data Venue Masih Kosong !</center>
                                    </h3>
                                    <p>
                                        <center>Venue kamu telah terkonfirmasi oleh admin, mari isi data venue anda dengan
                                            lengkap dan benar</center>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row justify-content-around">
                        @foreach ($venues as $venue)
                            <div class="da-card col-12 col-sm-6 col-md-4 col-lg-3 mb-4 mr-1">
                                <div class="da-card-photo position-relative" style="height: 200px; overflow: hidden;">
                                    @if ($venue->venueImages->isNotEmpty())
                                        <img src="/images/venues/Venue_Image/{{ $venue->venueImages->first()->image }}"
                                            alt="{{ $venue->venueImages->first()->image }}" class="img-fluid"
                                            style="max-height: 100%; object-fit: contain;">
                                    @else
                                        <div class="default-image"
                                            style="background-image: url('/images/venues/Venue_Image/default-venue.png');
                                    background-size: cover; background-position: center; background-repeat: no-repeat;
                                    width: 100%; height: 100%;">
                                        </div>
                                    @endif
                                    <div class="da-overlay da-slide-bottom">
                                        <div class="da-social">
                                            <ul class="clearfix">
                                                <div class="form-group mt-4">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <a class="btn btn-sm btn-outline-info btn-block"
                                                                href="{{ route('owner.venue.show', $venue->id) }}">
                                                                See Venue &ensp;<i class="fas fa-angle-double-right"></i>
                                                            </a>
                                                            <a class="btn btn-sm
                                                                @if ($venue->status == 0) btn-outline-info
                                                                @elseif ($venue->status == 1) btn-outline-success
                                                                @elseif ($venue->status == 2) btn-outline-danger @endif
                                                                btn-block"
                                                                @if ($venue->status == 1) href="{{ route('owner.venue.edit', $venue->id) }}"
                                                                @elseif ($venue->status == 2) onclick="editVenue({{ $venue->id }})"
                                                                href="javascript:void(0)"
                                                                @elseif ($venue->status == 0) onclick="confirmVenue()"
                                                                href="javascript:void(0)" @endif>
                                                                Edit Venue &ensp;<i class="fas fa-angle-double-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="da-card-content">
                                    {{-- status --}}
                                    <div class="w-100 d-flex justify-content-center">
                                        @if ($venue->status == 0)
                                            <span class="badge badge-info" style="width:100%">Menunggu Konfirmasi</span>
                                        @elseif ($venue->status == 1)
                                            <span class="badge badge-success" style="width:100%">Aktif</span>
                                        @elseif ($venue->status == 2)
                                            <span class="badge badge-danger" style="width:100%">Ditolak</span>
                                        @endif
                                    </div>
                                    <h5 class="h5 mb-10 mt-3">{{ $venue->name }}</h5>
                                    <p class="mb-0">
                                        <span style="display: inline-block; width: 100px;">Alamat Venue</span>
                                        : {{ ucwords($venue->address) }}
                                    </p>
                                    <p class="mb-0">
                                        <span style="display: inline-block; width: 100px;">CP Venue</span>
                                        : {{ ucwords($venue->phone_number) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheets')
    <style>
        .btn-width-200px {
            width: 100px !important;
        }
    </style>
@endpush

@stack('scripts')

<script>
    function confirmVenue() {
        Swal.fire({
            title: "Venue kamu belum dikonfirmasi !",
            text: "Harap menunggu admin untuk Approve venue kamu",
            icon: "info",
            confirmButtonColor: "info",
            confirmButtonText: "OK",
            customClass: {
                confirmButton: 'btn-width-200px'
            }
        });
    }

    function editVenue(id) {
        Swal.fire({
            title: "Venue ditolak, Apakah kamu ingin melengkapi data venue?",
            text: "Data venue yang telah dilengkapi akan diajukan lagi untuk dikonfirmasi oleh admin!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5cc744",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Lengkapi!",
            cancelButtonText: "Tidak, Batalkan!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('owner.venue.edit', $venue->id) }}";
            } else {
                Swal.fire("Dibatalkan", "Kamu batal melengkapi data venue", "error");
            }
        });
    }
</script>


{{-- belum dipake --}}
{{-- <script type="text/javascript">
    function warning() {
        swal({
                title: "Apakah kamu ingin melengkapi data ktp anda?",
                text: "Sebelum menambahkan venue, anda harus menginputkan ktp anda !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5cc744",
                confirmButtonText: "Ya, lengkapi!",
                cancelButtonText: "Tidak, batalkan!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    swal("Lengkapi data!", "Kamu akan dipindahkan ke halaman untuk melengkapi data ktp.",
                        "success");
                    location.href = "{{ route('owner.profile') }}";
                } else {
                    swal("Dibatalkan", "Kamu batal melengkapi data ktp", "error");
                }
            });
    }
</script> --}}
