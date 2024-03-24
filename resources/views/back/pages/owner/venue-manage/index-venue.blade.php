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
                            <h4 class="h4 text">List Data Venue</h4>
                        </div>
                        <div class="pull-right">
                            {{-- @if (Auth::user()->owner->ktp) --}}
                        <a style="float:right; margin-right:5px;" href="{{ route('owner.venue.create') }}">
                            <button class="btn btn-primary" type="button">Add Venue</button>
                        </a>
                        {{-- @else
                        <a style="float:right; margin-right:5px;" href="#">
                            <button onclick="warning()" class="btn btn-primary" type="button">Add Venue</button>
                        </a>
                        @endif --}}



                        {{-- <a href="{{ route('owner.venue.create')}}" class="btn btn-primary btn-sm" type="button">
                                <i class="fa fa-plus"></i> Add Venue
                            </a> --}}


                        </div>
                    </div>
                    <hr>
@if ($venues->count() == 0)
<div class="row justify-content-around">
    <div class="card">
    <div class="card-body">
        <h3><center>Data Venue Masih Kosong !</center> </h3>
        <p><center>Venue kamu telah terkonfirmasi oleh admin, mari isi data venue anda dengan lengkap dan benar</center></p>
    </div>
    </div>
    </div>
@endif


                    <div class="row justify-content-around">
                        @foreach ($venues as $venue)
                        {{-- card2 --}}
                    <div class="da-card col-sm-3">
                        <div class="da-card-photo" >
                            {{-- gambar venue --}}
                            <img src="/images/venues/venue-manage/contoh4.png" alt="">
                            <div class="da-overlay da-slide-bottom">
                                <div class="da-social">
                                    <ul class="clearfix">
                                        <div class="form-group mt-4">
                                            <div class="form-row">
                                                <div class="col">
                                                {{-- <a href="" type="button" class="btn btn-primary float-right">See Venue <i class="fa fa-arrow-right"></i></a> --}}
                                                <a class="btn btn-sm btn-primary float-right" @if ($venue->status ==
                                                    1)
                                                    href="{{ route('owner.venue.show', $venue->id) }}"
                                                    @elseif ($venue->status == 2) onclick="edit_venue({{$venue->id}})"
                                                    href="javascript:void(0)"
                                                    @elseif ($venue->status == 0) onclick="confirm_venue()"
                                                    href="javascript:void(0)"
                                                    @endif>
                                                    See Venue  &ensp;<i class="fa fa-arrow-right"></i>
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
                                @if($venue->status == 0)
                                <span class="badge badge-info" style="width:100%">Menunggu Konfirmasi</span>
                                @elseif ($venue->status == 1)
                                <span class="badge badge-success" style="width:100%">Aktif</span>
                                @elseif ($venue->status == 2)
                                <span class="badge badge-danger" style="width:100%">Ditolak</span>
                                @endif
                        </div>
                            <h5 class="h5 mb-10 mt-3">{{ $venue->name}}</h5>
                            <br>
                            <p class="mb-0">Address: {{ $venue->address}}</p>
                            <br>
                            <p class="mb-0">Phone  : {{ $venue->phone_number}}</p>
                        </div>
                    </div>
                    {{-- card2 close --}}
                    @endforeach

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@stack('scripts')
<script type="text/javascript">
    function edit(id, name, address, latitude, longitude) {
        if ($('#edit').is(":visible")) {
            $('#edit').hide('500');
        } else {
            console.log(id);
            $('#edit').show('500');
            $('#e_name').val(name);
            $('#e_address').val(address);
            $('#e_latitude').val(latitude);
            $('#e_longitude').val(longitude);
            $('#form-update').attr('action', "{{route('owner.venue.index')}}/" + id);
        }
    }

    function show(id, name, address, latitude, longitude) {
        $('#s_name').val(name);
        $('#s_address').val(address);
        $('#s_latitude').val(latitude);
        $('#s_longitude').val(longitude);
    }

    function confirm_venue(id) {
        swal({
            title: "Venue kamu belum dikonfirmasi !",
            text: "Harap menunggu admin untuk mengkonfirmasi venue kamu",
            type: "warning",
            confirmButtonColor: "#f02b2b",
            confirmButtonText: "OK",
            closeOnConfirm: false,
            closeOnCancel: false
        });
    }

    function edit_venue(id) {
        swal({
                title: "Apakah kamu ingin melengkapi data venue?",
                text: "Data venue yang telah dilengkapi akan diajukan lagi untuk dikonfirmasi oleh admin!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5cc744",
                confirmButtonText: "Ya, konfirmasi!",
                cancelButtonText: "Tidak, batalkan!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    swal("Lengkapi data!", "Kamu akan dipindahkan ke halaman untuk melengkapi data venue.",
                        "success");
                    location.href = "{{route('owner.venue.index')}}/" + id + "/edit?data=request";
                } else {
                    swal("Dibatalkan", "Kamu batal melengkapi data venue", "error");
                }
            });
    }

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
            function (isConfirm) {
                if (isConfirm) {
                    swal("Lengkapi data!", "Kamu akan dipindahkan ke halaman untuk melengkapi data ktp.",
                        "success");
                    location.href = "{{route('owner.profile')}}";
                } else {
                    swal("Dibatalkan", "Kamu batal melengkapi data ktp", "error");
                }
            });
    }
</script>


