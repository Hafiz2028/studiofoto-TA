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
                            <a href="{{ route('admin.home') }}">Home</a>
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
                        <br><br><a href="{{ route('owner.venue.edit', $venue->id) }}" class="btn btn-outline-success">Perbaiki Venue <i
                            class="fas fa-angle-double-right"></i></a>
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
    @endif

    <div class="product-wrap">
        <div class="product-detail-wrap mb-30">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="card-box mb-2 pt-3 pb-3 pl-4 pr-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="mb-3 text-center">{{ ucwords($venue->name) }}</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="profile-photo">
                                            <img src="/images/users/owners/OWNER_IMG_7361170979030965e954652fcca.jpg"
                                                alt="" class="avatar-photo" id="adminProfilePicture">
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
                                                        <strong>{{ $paymentMethodDetail->paymentMethod->name }}</strong>
                                                        <span style="color: #007bff;">(<span
                                                                onclick="copyToClipboard('{{ $paymentMethodDetail->no_rek }}')"
                                                                data-toggle="tooltip" title="Klik untuk menyalin"
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
                                                <li>
                                                    <a href="#" class="btn btn-check-out">
                                                        <span class="text">Wisuda dan orasd</span>
                                                        <span class="hover-text">Lihat Layanan</span>
                                                        <span class="icon"><i
                                                                class="fas fa-angle-double-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="btn btn-check-out">
                                                        <span class="text">Pre Wedding</span>
                                                        <span class="hover-text">Lihat Layanan</span>
                                                        <span class="icon"><i
                                                                class="fas fa-angle-double-right"></i></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="btn btn-check-out">
                                                        <span class="text">Keluarga</span>
                                                        <span class="hover-text">Lihat Layanan</span>
                                                        <span class="icon"><i
                                                                class="fas fa-angle-double-right"></i></span>
                                                    </a>
                                                </li>
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
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card-box mb-2 pl-2 pr-2 pt-3 pb-4 text-center">
                        <h5 class="mb-3">Surat Izin Mendirikan Usaha</h5>
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
                        <div class="photo-display">
                            @if (!empty($venue->imb))
                                <object data="http://studiofoto.test/images/venues/IMB/{{ $venue->imb }}"
                                    type="application/pdf" style="width: 100%; max-width: 200px; height: 281px;"
                                    class="document-display" id="imbDocument"
                                    sandbox="allow-scripts allow-same-origin allow-forms">
                                    <p>Browser Anda tidak mendukung menampilkan PDF secara langsung. Silakan <a
                                            href="http://studiofoto.test/images/venues/IMB/{{ $venue->imb }}">klik di
                                            sini</a> untuk melihat file PDF.</p>
                                </object>
                            @else
                                <img src="http://studiofoto.test/images/venues/IMB/default-surat.png" alt="Placeholder"
                                    style="width: 100%; max-width: 200px; height: 281px;">
                            @endif
                        </div>
                        <h5 class="mb-3 mt-3">Foto KTP</h5>
                        <p class="mt-3">Ada / Tidak Ada</p>
                        <img src="/images/users/owners/KTP_owner/ktp.png" alt="Foto KTP"
                            style="width: 100%; max-width: 300px; height: auto;">
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card-box p-3" style="background-color: #f8f9fa;">
                        <div class="image-name"></div>
                        <div class="product-slider owl-carousel main-slider">
                            <img src="/images/venues/Venue_Image/{{ $venue->picture }}" alt="{{ $venue->picture }}">
                            @foreach ($venue->venueImages as $image)
                                <img src="/images/venues/Studio_Image/{{ $image->image }}" alt="{{ $image->image }}">
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card-box pt-2 pl-3 pr-3 pb-3">
                        <br>
                        <h5 class="mb-3">Opening Hours</h5>
                        <div class="row">
                            @if ($uniqueDays->isEmpty())
                                <div class="col-md-12">
                                    <div class="alert alert-info">Tidak Ada jadwal buka Venue</div>
                                </div>
                            @else
                                @foreach ($uniqueDays as $uniqueDay)
                                    <div class="col-md-12">
                                        <div class="card card-danger shadow mb-3">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="text-white">{{ $uniqueDay->day->name }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="badges d-flex flex-wrap justify-content-between">
                                                    @if ($openingHours[$uniqueDay->day_id]->isEmpty())
                                                        <span class="badge badge-secondary mb-2">No hours available</span>
                                                    @else
                                                        @foreach ($openingHours[$uniqueDay->day_id] as $openingHour)
                                                            <span
                                                                class="badge {{ $openingHour->status == 2 ? 'badge-success' : 'badge-secondary' }} mr-2 mb-2"
                                                                title="{{ $openingHour->status == 2 ? 'Jadwal Buka' : 'Jadwal Tutup' }}"
                                                                data-toggle="tooltip">
                                                                {{ $openingHour->hour->hour }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                </div>
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
    {{-- style carousel nav --}}
    <style>
        .owl-carousel .owl-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            /* Menambahkan lebar agar tombol nav mengisi lebar carousel */
        }

        .owl-carousel .owl-nav button.owl-next,
        .owl-carousel .owl-nav button.owl-prev {
            position: absolute;
            background-color: rgba(21, 20, 20, 0.215);
            color: #fff;
            border: none;
            padding: 10px;
            width: 50px;
            height: 50px;
            font-size: 24px;
            /* Mengatur ukuran tombol nav */
        }

        .owl-carousel .owl-nav button.owl-next::before,
        .owl-carousel .owl-nav button.owl-prev::before {
            font-size: 30px;
            /* Ukuran ikon */
        }

        .owl-carousel .owl-nav button.owl-next {
            right: 0;
            /* Tombol next diletakkan di sebelah kanan */
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: 0;
            /* Tombol prev diletakkan di sebelah kiri */
        }
    </style>
    {{-- style carousel dot --}}
    <style>
        .owl-carousel .owl-dots {
            text-align: center;
            margin-top: 20px;
            /* Ubah sesuai kebutuhan */
        }

        .owl-carousel .owl-dot.active,
        .owl-carousel .owl-dot:hover,
        .owl-carousel .owl-dot.active:hover {
            background-color: #007bff !important;
            /* Warna dot yang dipilih */
        }

        .owl-carousel .owl-dot:not(.active):hover {
            background-color: #007bff !important;
            /* Warna dot yang tidak dipilih saat dihover */
        }

        .owl-carousel .owl-dot {
            display: inline-block !important;
            width: 10px !important;
            height: 10px !important;
            background-color: #ccc !important;
            border-radius: 50% !important;
            margin: 0 5px !important;
        }
    </style>
@endpush
@push('scripts')
    {{-- fungsi salin norek --}}
    <script>
        function copyToClipboard(text) {
            var dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
            Swal.fire({
                icon: 'success',
                title: 'Nomor rekening telah disalin:',
                text: text
            });
        }

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    {{-- carousel --}}
    <script>
        $(document).ready(function() {
            $(".main-slider").owlCarousel({
                items: 1,
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                nav: true,
                dots: true,
            });
        });
    </script>
@endpush
