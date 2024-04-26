@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Detail Venue')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Detail Venue</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.venue.index') }}">Venue's Manage</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail Venue
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if ($venue->status == 2)
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card-box">
                    <div class="alert alert-danger text-center">
                        <strong style="font-size: 20px;">PERHATIAN</strong><br> Venue ini telah ditolak.
                        @if (!empty($venue->reject_note))
                            <br>
                            <span class="text-center"><strong>Alasan penolakan:</strong><br><br>
                                {{ ucwords($venue->reject_note) }}</span>
                        @endif
                        <br><br>
                        <a href="" class="btn btn-outline-danger">Hapus Venue
                        </a>
                        <a href="{{ route('owner.venue.edit', $venue->id) }}" class="btn btn-success">Perbaiki Venue <i
                                class="fas fa-angle-double-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($venue->status == 0)
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card-box">
                    <div class="alert alert-info text-center">
                        <strong style="font-size: 20px;">PERHATIAN</strong><br>
                        <p class="pb-0 mb-0">Venue ini Belum Di Approve oleh Admin. <br>Silahkan untuk menunggu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($venue->status == 1)
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card-box">
                    <div
                        class="alert alert-success d-flex justify-content-between align-items-center flex-column flex-sm-row">
                        <div>
                            <strong style="font-size: 20px;">SELAMAT</strong><br>
                            <p class="pb-0 mb-0">Venue telah di Approve oleh Admin.</p>
                        </div>
                        <div class="actions-buttons d-flex justify-content-sm-end mt-3 mt-sm-0">
                            <a href="route" class="btn btn-outline-danger mr-2 mr-sm-1 mb-2 mb-sm-0">
                                <i class="fas fa-trash"></i> Hapus Venue
                            </a>
                            <a href="{{ route('owner.venue.edit', $venue->id) }}"
                                class="btn btn-outline-info mr-2 mr-sm-1 mb-2 mb-sm-0">
                                <i class="fas fa-edit"></i> Update Venue
                            </a>
                            <a href="{{ route('owner.venue.services.create', $venue->id) }}"
                                class="btn btn-primary mb-2 mb-sm-0">
                                <i class="fas fa-plus"></i> Tambah Event Layanan Studio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="product-wrap">
        <div class="product-detail-wrap mb-30">
            <div class="row">
                {{-- card detail venue --}}
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="card card-primary shadow">
                        <div class="card-header bg-info text-white">
                            <h2 class="text-white mb-0 text-center">{{ ucwords($venue->name) }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="profile-photo">
                                        @if ($venue->owner->picture && file_exists(public_path("/images/users/owners/$venue->owner->picture")))
                                            <img src="{{ asset("/images/users/owners/$venue->owner->picture") }}"
                                                alt="" class="avatar-photo" id="adminProfilePicture">
                                        @else
                                            <img src="{{ asset('/images/users/default-avatar.png') }}" alt=""
                                                class="avatar-photo" id="adminProfilePicture">
                                        @endif
                                    </div>
                                    <h6 class="mb-2">
                                        <span style="display: inline-block; width: 135px;">Pemilik</span>
                                        : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                            {{ ucwords($venue->owner->name) }}</p>
                                    </h6>
                                    <h6 class="mb-2">
                                        <span style="display: inline-block; width: 135px;">CP Pemilik</span>
                                        : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                            {{ ucwords($venue->owner->handphone) }}</p>
                                    </h6>
                                    <h6 class="mb-2">
                                        <span style="display: inline-block; width: 135px;">Alamat Pemilik</span>
                                        : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                            {{ ucwords($venue->owner->address) }}</p>
                                    </h6>
                                    <h6 class="mb-2">
                                        <span style="display: inline-block; width: 135px;">CP Venue</span>
                                        : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                            {{ ucwords($venue->phone_number) }}</p>
                                    </h6>
                                    <h6 class="mb-2">
                                        <span style="display: inline-block; width: 135px;">Alamat Venue</span>
                                        : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                            {{ ucwords($venue->address) }}</p>
                                    </h6>
                                    <h6 class="mb-3 mt-3">
                                        <span style="display: inline-block; width: 135px;">Deskripsi</span>
                                        :
                                    </h6>
                                    @if ($venue->information)
                                        <p class="ml-3" style="text-align: justify;">
                                            {{ ucfirst($venue->information) }}
                                        </p>
                                    @else
                                        <div class="alert alert-info ml-3">Tidak ada deskripsi venue.</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3 mt-3">
                                        <span style="display: inline-block; width: 170px;">Metode Pembayaran</span>
                                        :
                                    </h6>
                                    <ul class="ml-3" style="list-style-type: none;">
                                        @if (isset($payment_method_detail) && $payment_method_detail->count() > 0)
                                            @foreach ($payment_method_detail as $paymentMethodDetail)
                                                <li>
                                                    <img src="{{ asset('images/icon_bank/' . $paymentMethodDetail->paymentMethod->icon) }}"
                                                        alt="{{ $paymentMethodDetail->paymentMethod->name }}"
                                                        width="24" height="24">
                                                    <strong
                                                        style="display: inline-block; width: 110px;">{{ $paymentMethodDetail->paymentMethod->name }}</strong>
                                                    <span style="color: #007bff;">(<span
                                                            onclick="copyToClipboard('{{ $paymentMethodDetail->no_rek }}', '{{ $paymentMethodDetail->paymentMethod->name }}')"
                                                            data-toggle="tooltip"
                                                            title="Klik untuk menyalin nomor {{ $paymentMethodDetail->paymentMethod->name }}"
                                                            style="cursor: pointer; text-decoration: underline; color: #007bff;">{{ $paymentMethodDetail->no_rek }}</span>)</span>
                                                </li>
                                            @endforeach
                                        @else
                                            <div class="alert alert-info text-center mr-4 ml-1">Tidak ada Metode
                                                Pembayaran</div>
                                        @endif
                                    </ul>
                                    <h6 class="mb-3 mt-3">
                                        <span style="display: inline-block; width: 170px;">Event Layanan Studio</span>
                                        :
                                    </h6>
                                    @if ($venue->status == 1)
                                        <ul class="ml-3" style="list-style-type: none;">
                                            @foreach ($service_events as $service_event)
                                                <li>
                                                    <a href="{{ route('owner.venue.services.show', ['venue' => $venue->id, 'service' => $service_event->id]) }}" class="btn btn-check-out">
                                                        <span class="text">{{ $service_event->name }}</span>
                                                        <span class="hover-text">Lihat Layanan</span>
                                                        <span class="icon"><i
                                                        class="fas fa-angle-double-right"></i></span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @elseif ($venue->status == 0)
                                        <div class="alert alert-warning text-center ml-4 mr-4">
                                            Venue Belum di Approve
                                        </div>
                                    @elseif ($venue->status == 2)
                                        <div class="alert alert-danger text-center ml-4 mr-4">
                                            Venue Ditolak
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p class="text-center">MAP STUDIO FOTO</p>
                        </div>
                    </div>
                </div>
                {{-- card IMB --}}
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-primary shadow mb-3 text-center">
                        <div class="card-header bg-info text-white">
                            <h5 class="text-white mb-3">Surat Izin Mendirikan Usaha</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="photo-display">
                                @if (!empty($venue->imb))
                                    <object data="http://studiofoto.test/images/venues/IMB/{{ $venue->imb }}"
                                        type="application/pdf" style="width: 100%; max-width: 200px; height: 281px;"
                                        class="document-display mb-2" id="imbDocument"
                                        sandbox="allow-scripts allow-same-origin allow-forms">
                                        <p>Browser Anda tidak mendukung menampilkan PDF secara langsung. Silakan <a
                                                href="http://studiofoto.test/images/venues/IMB/{{ $venue->imb }}">klik
                                                di
                                                sini</a> untuk melihat file PDF.</p>
                                    </object>
                                @else
                                    <img src="http://studiofoto.test/images/venues/IMB/default-surat.png"
                                        alt="Placeholder" style="width: 100%; max-width: 200px; height: 281px;">
                                @endif
                            </div>
                            @if ($venue->imb)
                                <div class="alert alert-success" role="alert"
                                    style="display: inline-block; padding: 0.5rem 1rem; border-width: 1px 0.2em;">
                                    <span class="text-nowrap">Ada</span>
                                </div>
                            @else
                                <div class="alert alert-danger" role="alert"
                                    style="display: inline-block; padding: 0.5rem 1rem; border-width: 1px 0.2em;">
                                    <span class="text-nowrap">Tidak Ada</span>
                                </div>
                            @endif
                            <h5 class="mb-3 mt-3">Foto KTP</h5>
                            <p class="mt-3">Ada / Tidak Ada</p>
                            <img src="/images/users/owners/KTP_owner/ktp.png" alt="Foto KTP"
                                style="width: 100%; max-width: 300px; height: auto;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- tampilkan image --}}
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white mb-2">
                            <h5 class="text-center text-white mb-0">Venue Image</h5>
                        </div>
                        <div class="card-body">
                            @if ($venue->venueImages->isEmpty())
                                <div class="alert alert-danger text-center">
                                    Tidak ada Gambar
                                </div>
                            @else
                                <div id="venueImageCarousel" class="carousel slide" data-ride="carousel"
                                    data-interval="5000">
                                    <ol class="carousel-indicators">
                                        @foreach ($venue->venueImages as $index => $image)
                                            <li data-target="#venueImageCarousel" data-slide-to="{{ $index }}"
                                                class="{{ $index == 0 ? 'active' : '' }}"></li>
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach ($venue->venueImages as $index => $image)
                                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                <img src="/images/venues/Venue_Image/{{ $image->image }}"
                                                    alt="{{ $image->image }}" class="d-block w-100"
                                                    style="box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);">
                                            </div>
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#venueImageCarousel" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#venueImageCarousel" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- card jadwal --}}
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card shadow">
                        <div class="card-header bg-info text-center">
                            <h5 class="mb-0 text-white">Jadwal Buka Venue</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if ($uniqueDays->isEmpty())
                                    <div class="col-md-12">
                                        <div class="alert alert-info">Tidak Ada jadwal buka Venue</div>
                                    </div>
                                @else
                                    @foreach ($uniqueDays as $uniqueDay)
                                        <div class="col-md-12">
                                            <div class="card mb-3">
                                                <div class="card-header bg-primary">
                                                    <h6 class="mb-0 text-white">{{ $uniqueDay->day->name }}</h6>
                                                </div>
                                                <div
                                                    class="card-body d-flex flex-wrap justify-content-center align-items-start">
                                                    @if ($openingHours[$uniqueDay->day_id]->isEmpty())
                                                        <span class="badge badge-secondary">No hours available</span>
                                                    @else
                                                        @foreach ($openingHours[$uniqueDay->day_id] as $index => $openingHour)
                                                            <span
                                                                class="badge {{ $openingHour->status == 2 ? 'badge-success' : 'badge-secondary' }} mr-2 mb-2"
                                                                style="width: 50px; height: 30px; line-height: 30px; padding: 0 8px;"
                                                                title="{{ $openingHour->status == 2 ? 'Jadwal Buka' : 'Jadwal Tutup' }}"
                                                                data-toggle="tooltip">
                                                                {{ $openingHour->hour->hour }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('stylesheets')
    {{-- button event layanan --}}
    <style>
        .btn-check-out {
            position: relative;
            display: inline-block;
            padding: 10px 30px;
            border: 2px solid #007bff;
            border-radius: 5px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s, background-color 0.3s;
            overflow: hidden;
            margin: 2px 0;
            width: 280px;
            max-width: 100%;
            text-align: center;
        }

        .btn-check-out:hover {
            background-color: #007bff;
        }

        .btn-check-out .hover-text {
            position: absolute;
            left: -100%;
            top: center;
            opacity: 0;
            color: #ffffff;
            transition: opacity 0.3s, left 0.3s;
        }

        .btn-check-out:hover .hover-text {
            opacity: 1;
            left: 35%;
            text-align: center;
        }

        .btn-check-out .icon {
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            color: #ffffff;
            transform: translateY(-50%);
            transition: left 0.3s;
        }

        .btn-check-out:hover .icon {
            left: calc(100% - 30px);
        }

        .btn-check-out .text {
            opacity: 1;
        }

        .btn-check-out:hover .text {
            opacity: 0;
        }

        @media (max-width: 768px) {
            .btn-check-out {
                width: 200px;
                padding: 10px;
            }

            .btn-check-out:hover .hover-text {
                opacity: 1;
                left: 25%;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .btn-check-out {
                width: 200px;
                padding: 10px;
                max-width: 100%;
            }

            .btn-check-out:hover .hover-text {
                opacity: 1;
                left: 25%;
                text-align: center;
            }
        }
    </style>
    <style>
        .carousel-control-prev,
        .carousel-control-next {
            border-radius: 10px;
            border-width: 10px;
        }

        .carousel-indicators li {
            background-color: #ffffff;
            border-radius: 0;
            border-width: 30px;
        }

        .carousel-control-prev,
        .carousel-control-next,
        .carousel-indicators {
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>
@endpush
@push('scripts')
    {{-- fungsi salin norek --}}
    <script>
        function copyToClipboard(text, paymentMethodName) {
            var dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
            Swal.fire({
                icon: 'success',
                title: 'Nomor ' + paymentMethodName + ' telah disalin:',
                text: text
            });
        }

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
