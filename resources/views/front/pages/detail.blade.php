@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Foto Yuk | Detail Venue')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/front/img/tomat.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Detail Venue</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Detail Venue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    <x-alert.form-alert />
    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            @if ($venue->venueImages->isNotEmpty())
                                <img class="product__details__pic__item--large"
                                    src="/images/venues/Venue_Image/{{ $venue->venueImages->first()->image }}"
                                    alt="">
                            @else
                                <img class="product__details__pic__item--large"
                                    src="/images/venues/Venue_Image/default-venue.png" alt="">
                            @endif
                        </div>
                        <div class="product__details__pic__slider owl-carousel">
                            @foreach ($venue->venueImages as $image)
                                <img data-imgbigurl="/images/venues/Venue_Image/{{ $image->image }}"
                                    src="/images/venues/Venue_Image/{{ $image->image }}" alt="">
                            @endforeach
                            @foreach ($venue->serviceEvents as $serviceEvent)
                                @foreach ($serviceEvent->serviceEventImages as $image)
                                    <img data-imgbigurl="/images/venues/Service_Image/{{ $image->image }}"
                                        src="/images/venues/Service_Image/{{ $image->image }}" alt="">
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3>{{ ucwords(strtolower($venue->name)) }}</h3>
                        <div class="product__details__price" style="color:#e27201;">
                            @if ($minPrice == 0 && $maxPrice == 0)
                                Tidak ada paket foto
                            @else
                                Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp
                                {{ number_format($maxPrice, 0, ',', '.') }}
                            @endif
                        </div>
                        <ul>
                            <li><b>Alamat</b> <span>{{ ucwords(strtolower($venue->address)) }},
                                    {{ ucwords(strtolower($venue->village->name)) }},
                                    {{ ucwords(strtolower($venue->village->district->name)) }}.</span></li>
                            <li><b>Jadwal Buka</b> <span>
                                    @foreach ($venue->uniqueActiveDays() as $day)
                                        <div class="badge badge-info">{{ $day->name }}</div>
                                    @endforeach
                                </span></li>
                            <li><b>Pemilik</b> <span>{{ ucwords(strtolower($venue->owner->name)) }}</span></li>
                            <li><b>Deskripsi</b>
                                <p>{{ $venue->information }}</p>
                            </li>
                        </ul>
                        <div>
                            <a href="javascript:;" class="btn btn-outline-info btn-outline-info-custom"
                                data-toggle="tooltip" title="Cek Paket Harga Venue" id="katalogBtn"
                                style="padding: 16px 28px 14px; margin-right: 6px; margin-bottom: 5px; display: inline-block; font-size: 14px; text-transform: uppercase; font-weight: 700; letter-spacing: 2px;">
                                KATALOG
                            </a>
                            @include('front.pages.catalog.catalog', ['venue' => $venue])
                            <a href="https://wa.me/{{ $venue->phone_number }}?text={{ urlencode('Halo, saya ingin booking jadwal studio foto.') }}"
                                target="_blank" data-toggle="tooltip" title="Chat Contact Person Studio Foto"
                                data-placement="auto" class="whatsapp-link">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            @if (filter_var($venue->map_link, FILTER_VALIDATE_URL))
                                <a href="{{ $venue->map_link }}" target="_blank" data-toggle="tooltip"
                                    title="Lihat Lokasi di Maps" class="maps-link">
                                    <i class="fas fa-map-marked-alt"></i>
                                </a>
                            @else
                                <a href="#" onclick="swalAlert(); return false;" target="_blank" data-toggle="tooltip"
                                    title="Lihat Lokasi di Maps" class="maps-link">
                                    <i class="fas fa-map-marked-alt"></i>
                                </a>
                            @endif
                            @if (auth()->check() && auth()->user()->role === 'customer')
                                <a href="{{ route('customer.booking.create') }}" class="primary-btn" data-toggle="modal"
                                    data-target="#bookingModal">BOOKING</a>
                                @include('back.pages.owner.booking-manage.create')
                            @else
                                <a href="javascript:;" id="openDetailVenue" class="primary-btn" data-toggle="modal"
                                    data-target="#openDetailVenue">BOOKING</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">Jadwal Buka</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                                    aria-selected="false">Jenis Layanan
                                    <span>({{ $venue->serviceEvents->count() }})</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                    aria-selected="false">Tentang</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6 class="text-center">List Jadwal Buka Studio</h6>
                                    @php
                                        $sortedUniqueDayIds = collect($uniqueDayIds)->sort()->values()->all();
                                    @endphp
                                    <div class="row justify-content-center">
                                        @if (empty($sortedUniqueDayIds))
                                            <div class="col-md-12">
                                                <div class="alert alert-info">Tidak Ada jadwal buka Venue</div>
                                            </div>
                                        @else
                                            @foreach ($sortedUniqueDayIds as $dayId)
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="card shadow mb-3">
                                                        <div class="card-header bg-info">
                                                            <h6 class="mb-0 text-white text-center">
                                                                {{ $openingHours->firstWhere('day_id', $dayId)->day->name }}
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="card-body d-flex flex-wrap justify-content-center align-items-start">
                                                            @php
                                                                $hoursForDay = $openingHours->where('day_id', $dayId);
                                                            @endphp
                                                            @if ($hoursForDay->isEmpty())
                                                                <span class="badge badge-secondary">No hours
                                                                    available</span>
                                                            @else
                                                                @foreach ($hoursForDay as $openingHour)
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
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6 class="text-center">Katalog Jenis Layanan </h6>
                                    <div class="row justify-content-center">
                                        @foreach ($venue->serviceEvents as $serviceEvent)
                                            <div class="col-lg-4 col-md-12">
                                                <div class="card shadow mb-3">
                                                    <div class="card-header bg-info">
                                                        <h6 class="mb-0 text-white text-center">
                                                            {{ $serviceEvent->name }}
                                                        </h6>
                                                        <p class="mb-0 text-white text-center">
                                                            {{ $serviceEvent->serviceType->service_name }}</p>
                                                    </div>
                                                    <div
                                                        class="card-body d-flex flex-wrap justify-content-center align-items-start">
                                                        <div class="photo-display text-center">
                                                            @if (!empty($serviceEvent->catalog))
                                                                <a href="/images/venues/Katalog/{{ $serviceEvent->catalog }}"
                                                                    data-lightbox="catalog">
                                                                    <img src="/images/venues/Katalog/{{ $serviceEvent->catalog }}"
                                                                        alt="Katalog Image {{ $serviceEvent->name }}"
                                                                        style="width: 100%; max-width: 200px; height: 281px;"
                                                                        class="document-display mb-2">
                                                                </a>
                                                            @else
                                                                <a href="/images/venues/IMB/default-surat.png"
                                                                    data-lightbox="catalog">
                                                                    <img src="/images/venues/IMB/default-surat.png"
                                                                        alt="Tidak ada Katalog"
                                                                        style="width: 100%; max-width: 200px; height: 281px;"
                                                                        class="document-display mb-2">
                                                                </a>
                                                            @endif
                                                            <ul class="text-center my-3">
                                                                <b>Deskripsi</b>
                                                                <p class="text-center mt-3">
                                                                    {{ $serviceEvent->description }}
                                                                </p>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6 class="text-center">Informasi Tambahan Venue</h6>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="ml-5"><b>Nama Pemilik</b>
                                                <p>{{ ucwords(strtolower($venue->owner->user->name)) }}</p>
                                            </ul>
                                            <ul class="ml-5"> <b>Nomor Hp Pemilik</b>
                                                <p>{{ ucwords(strtolower($venue->owner->user->handphone)) }}</p>
                                            </ul>
                                            <ul class="ml-5"> <b>Alamat Pemilik</b>
                                                <p>{{ ucwords(strtolower($venue->owner->user->address)) }}</p>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <b>Metode Pembayaran</b>
                                            @if ($payment_method_detail->count() > 0)
                                                @foreach ($payment_method_detail as $paymentMethodDetail)
                                                    <ul class="ml-3">
                                                        <p>
                                                            <img src="{{ asset('images/icon_bank/' . $paymentMethodDetail->paymentMethod->icon) }}"
                                                                alt="{{ $paymentMethodDetail->paymentMethod->name }}"
                                                                width="32" height="32">
                                                            <strong
                                                                style="display: inline-block; width: 110px;">{{ $paymentMethodDetail->paymentMethod->name }}</strong>
                                                            <span style="color: #007bff;">(<span
                                                                    onclick="copyToClipboard('{{ $paymentMethodDetail->no_rek }}', '{{ $paymentMethodDetail->paymentMethod->name }}')"
                                                                    data-toggle="tooltip"
                                                                    title="Klik untuk menyalin nomor {{ $paymentMethodDetail->paymentMethod->name }}"
                                                                    style="cursor: pointer; text-decoration: underline; color: #007bff;">{{ $paymentMethodDetail->no_rek }}</span>)</span>
                                                        </p>
                                                    </ul>
                                                @endforeach
                                            @else
                                                <div class="alert alert-info text-center mr-4 ml-1">Tidak ada Metode
                                                    Pembayaran</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->

    <!-- Related Product Section Begin -->
    <section class="related-product">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title related__product__title">
                        <h2>Studio Foto Lainnya</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @if ($venues->isEmpty())
                    <div class="col-lg-12">
                        <div class="alert alert-warning text-center" role="alert">
                            Tidak Ada Venue Lainnya
                        </div>
                    </div>
                @else
                    @foreach ($venues as $item)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg"
                                    @if ($item->venueImages->isNotEmpty()) data-setbg="/images/venues/Venue_Image/{{ $item->venueImages->first()->image }}"
                        alt="{{ $item->venueImages->first()->image }}"
                        style="background-image: url('/images/venues/Venue_Image/{{ $item->venueImages->first()->image }}');"
                        @else
                            data-setbg="/images/venues/Venue_Image/default-venue.png"
                            alt="Tidak Ada Gambar Venue" @endif>
                                    <ul class="product__item__pic__hover">
                                        <li>
                                            @if (filter_var($item->map_link, FILTER_VALIDATE_URL))
                                                <a href="{{ $item->map_link }}" target="_blank"
                                                    onclick="return openLink(event, '{{ $item->map_link }}')">
                                                    <i class="icon-copy fa fa-map-marker" data-toggle="tooltip"
                                                        title="Lihat Lokasi di Maps" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <a href="#" onclick="swalAlert(); return false;">
                                                    <i class="icon-copy fa fa-map-marker" data-toggle="tooltip"
                                                        title="Lihat Lokasi di Maps" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        </li>
                                        <li>
                                            @if (auth()->guard('customer')->check())
                                                <a href="{{ route('customer.detail-venue', $item->id) }}">
                                                    <i class="fas fa-info" data-toogle="tooltip"
                                                        title="Detail Studio Foto" data-placement="auto"></i></a>
                                            @else
                                                <a href="{{ route('customer.detail-venue-not-login', $item->id) }}">
                                                    <i class="fas fa-info" data-toogle="tooltip"
                                                        title="Detail Studio Foto" data-placement="auto"></i></a>
                                            @endif
                                        </li>
                                        <li>
                                            @php
                                                $phone_number = $item->phone_number;
                                                if (substr($phone_number, 0, 2) == '08') {
                                                    $phone_number = '628' . substr($phone_number, 2);
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $phone_number }}?text={{ urlencode('Halo, saya ingin booking jadwal studio foto.') }}"
                                                target="_blank" data-toogle="tooltip" title="Chat pihak Studio Foto"
                                                data-placement="auto">
                                                <i class="fab fa-whatsapp" style="font-size:6mm;"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    @if (auth()->guard('customer')->check())
                                        <h6><a
                                                href="{{ route('customer.detail-venue', $item->id) }}">{{ $item->name }}</a>
                                        </h6>
                                    @else
                                        <h6><a
                                                href="{{ route('customer.detail-venue-not-login', $item->id) }}">{{ $item->name }}</a>
                                        </h6>
                                    @endif
                                    <h5 style="font-size: 16px; color: #333; margin-top: 10px; text-align:left;">
                                        <span
                                            style="display: inline-block; font-size: 12px; vertical-align: super; font-weight: normal;">Start
                                            from</span> Rp.
                                        {{ number_format($item->min_price ?? 0, 2, ',', '.') }}
                                    </h5>
                                    {{-- <h6 style="text-align: left;"> {{$phone_number}}</h6> --}}
                                    <p class="mt-2" style="text-align: left;">
                                        {{ ucwords(strtolower($item->address)) }},
                                        {{ ucwords(strtolower($item->village->district->name)) }},
                                        {{ ucwords(strtolower($item->village->name)) }},
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- Related Product Section End -->


    {{-- create booking --}}
    <script>
        var packages = [];
        var packageDetails = [];

        function populatePackageAndDetails() {
            resetSelectAndDisable('package_detail', 'Pilih Paket Foto...');
            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';
            document.getElementById('date').value = '';
            var serviceEventId = document.getElementById('service').value;
            var packageDetailSelect = document.getElementById('package_detail');
            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
            fetch(`/api/packages/${serviceEventId}`)
                .then(response => response.json())
                .then(data => {
                    packages = data;
                    packageDetails = packages.reduce((acc, pkg) => acc.concat(pkg.service_package_details), []);
                    console.log('Package Details populate:', packageDetails);
                    packages.forEach(pkg => {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = pkg.name;

                        if (pkg.service_package_details && pkg.service_package_details.length > 0) {
                            pkg.service_package_details.forEach(detail => {
                                const option = document.createElement('option');
                                option.value = detail.id;
                                option.setAttribute('data-price', detail.price);
                                option.text =
                                    `${pkg.name} - (${detail.sum_person}) Orang - Rp ${formatCurrency(detail.price)}`;
                                option.dataset.printPhotoDetails = JSON.stringify(pkg
                                    .print_photo_details);
                                option.dataset.framePhotoDetails = JSON.stringify(pkg
                                    .frame_photo_details);
                                option.dataset.addOnPackageDetails = JSON.stringify(pkg
                                    .add_on_package_details);
                                optgroup.appendChild(option);
                            });
                            packageDetailSelect.appendChild(optgroup);
                        }
                    });
                    enableSelect('package_detail');
                })
                .catch(error => console.error('Error:', error));
        }

        function getPriceByPackageDetailId(selectedPackageDetailId) {
            var packageDetail = packageDetails.find(detail => detail.id == selectedPackageDetailId);
            if (packageDetail) {
                return packageDetail.price;
            }
            return null;
        }

        function updatePaymentMethodBadge() {
            var badgeContainer = document.getElementById('badge-container');
            var detailContainer = document.getElementById('detail-container');
            var packageDetailSelect = document.getElementById('package_detail');
            var selectedPackageDetailId = packageDetailSelect.value;
            var selectedOption = packageDetailSelect.options[packageDetailSelect.selectedIndex];
            var price = selectedOption.getAttribute('data-price');

            if (price !== null) {
                var packageDetail = packageDetails.find(detail => detail.id == selectedPackageDetailId);
                if (packageDetail) {
                    var selectedPackage = packages.find(pkg => pkg.id == packageDetail.service_package_id);
                    if (!selectedPackage) {
                        console.error('Package not found for packageDetailId:', selectedPackageDetailId);
                        return;
                    }
                    badgeContainer.innerHTML = '';
                    var timeBadge = document.createElement('div');
                    timeBadge.classList.add('badge', 'mb-1');
                    var timeStatus = packageDetail.time_status;
                    switch (timeStatus) {
                        case 0:
                            timeBadge.classList.add('badge-success');
                            timeBadge.textContent = '30 Menit';
                            break;
                        case 1:
                            timeBadge.classList.add('badge-primary');
                            timeBadge.textContent = '60 Menit';
                            break;
                        case 2:
                            timeBadge.classList.add('badge-info');
                            timeBadge.textContent = '90 Menit';
                            break;
                        case 3:
                            timeBadge.classList.add('badge-warning');
                            timeBadge.textContent = '120 Menit';
                            break;
                        default:
                            timeBadge.classList.add('badge-danger');
                            timeBadge.textContent = 'Waktu Tidak Valid';
                    }
                    badgeContainer.appendChild(timeBadge);
                    var paymentBadge = document.createElement('div');
                    paymentBadge.classList.add('badge', 'mt-1');
                    var dpStatus = selectedPackage.dp_status;
                    switch (dpStatus) {
                        case 0:
                            paymentBadge.classList.add('badge-info');
                            paymentBadge.textContent = 'Hanya Lunas';
                            break;
                        case 1:
                            paymentBadge.classList.add('badge-success');
                            paymentBadge.textContent = `DP Minimal ${selectedPackage.dp_percentage * 100}%`;
                            break;
                        case 2:
                            paymentBadge.classList.add('badge-success');
                            var minimalPayment = Math.round(selectedPackage.dp_min / 1000) * 1000;
                            var minPayment = minimalPayment.toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            });
                            paymentBadge.textContent = `Min. Bayar ${minPayment}`;
                            break;
                        default:
                            paymentBadge.classList.add('badge-danger');
                            paymentBadge.textContent = 'Tidak Ada Metode Pembayaran';
                    }
                    badgeContainer.appendChild(paymentBadge);

                    var totalPriceInput = document.getElementById('total-price-input');
                    totalPriceInput.value = price;

                    detailContainer.innerHTML = '';
                    var priceDetail = document.createElement('h6');
                    priceDetail.classList.add('mb-2');
                    priceDetail.innerHTML =
                        `<span style="display: inline-block; width: 160px;">Harga Paket</span>: <p style="display: inline-block; margin: 0; font-weight: normal;"><div class="badge badge-success ml-1"><i class="icon-copy dw dw-money"></i> Rp ${parseInt(price).toLocaleString('id-ID')}</div></p>`;
                    detailContainer.appendChild(priceDetail);

                    var addOnPackageDetails = JSON.parse(selectedOption.dataset.addOnPackageDetails);
                    var addOnPackageDetailHTML = addOnPackageDetails.map(detail => {
                            return `<div class="badge badge-info ml-1"><i class="icon-copy dw dw-photo-camera1"></i> ${detail.sum} ${detail.add_on_package.name}</div>`;
                        }).join('') ||
                        '<div class="badge badge-warning"><i class="icon-copy dw dw-photo-camera1"></i> Tidak include Add On</div>';
                    var addOnPackageDetailElement = document.createElement('h6');
                    addOnPackageDetailElement.classList.add('mb-2');
                    addOnPackageDetailElement.innerHTML =
                        `<span style="display: inline-block; width: 160px;">Include Add On</span>: <p style="display: inline-block; margin: 0; font-weight: normal;">${addOnPackageDetailHTML}</p>`;
                    detailContainer.appendChild(addOnPackageDetailElement);

                    var printPhotoDetails = JSON.parse(selectedOption.dataset.printPhotoDetails);
                    var printPhotoDetailHTML = printPhotoDetails.map(detail => {
                            return `<div class="badge badge-secondary ml-1"><i class="icon-copy dw dw-print"></i> Size ${detail.print_photo.size}</div>`;
                        }).join('') ||
                        '<div class="badge badge-warning"><i class="icon-copy dw dw-print"></i> Tidak include Cetak Foto</div>';
                    var printPhotoDetailElement = document.createElement('h6');
                    printPhotoDetailElement.classList.add('mb-2');
                    printPhotoDetailElement.innerHTML =
                        `<span style="display: inline-block; width: 160px;">Include Cetak Foto</span>: <p style="display: inline-block; margin: 0; font-weight: normal;">${printPhotoDetailHTML}</p>`;
                    detailContainer.appendChild(printPhotoDetailElement);

                    var framePhotoDetails = JSON.parse(selectedOption.dataset.framePhotoDetails);
                    var framePhotoDetailHTML = framePhotoDetails.map(detail => {
                            return `<div class="badge badge-secondary ml-1"><i class="icon-copy dw dw-image1"></i> Size ${detail.print_photo.size}</div>`;
                        }).join('') ||
                        '<div class="badge badge-warning"><i class="icon-copy dw dw-image1"></i> Tidak include frame</div>';
                    var framePhotoDetailElement = document.createElement('h6');
                    framePhotoDetailElement.classList.add('mb-2');
                    framePhotoDetailElement.innerHTML =
                        `<span style="display: inline-block; width: 160px;">Include Frame Foto</span>: <p style="display: inline-block; margin: 0; font-weight: normal;">${framePhotoDetailHTML}</p>`;
                    detailContainer.appendChild(framePhotoDetailElement);
                }
            }
        }

        function toggleDateInput() {
            console.log('toggleDateInput called');
            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';
            document.getElementById('date').value = '';
            var packageDetailSelect = document.getElementById('package_detail');
            var dateInput = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');
            var isPackageSelected = packageDetailSelect.value !== '';
            var isDisabled = !isPackageSelected;
            dateInput.disabled = isDisabled;
            console.log('isPackageSelected:', isPackageSelected);
            if (!isDisabled) {
                $(dateInput).datepicker('enable');
                console.log('datepicker enabled');
            } else {
                $(dateInput).datepicker('disable');
                console.log('datepicker disable');
            }
            if (isDisabled) {
                scheduleContainer.innerHTML = '';
                var infoAlert = document.createElement('div');
                infoAlert.classList.add('alert', 'alert-info');
                infoAlert.textContent = 'Belum memilih tanggal dan venue';
                scheduleContainer.appendChild(infoAlert);
            }
        }

        function resetSelectAndDisable(id, placeholder) {
            var select = document.getElementById(id);
            select.innerHTML = '<option value="" disabled selected>' + placeholder + '</option>';
            select.setAttribute('disabled', true);
        }

        function enableSelect(id) {
            document.getElementById(id).removeAttribute('disabled');
        }

        function formatCurrency(amount) {
            return amount.toLocaleString('id-ID');
        }
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded event fired');
            var selectedPackageDetailId = null;
            var serviceEventSelect = document.getElementById('service');
            var packageDetailSelect = document.getElementById('package_detail');
            var dateSelect = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');
            var dateInput = document.getElementById('date');
            var venueIdInput = document.getElementById('venue_id');
            var openingHoursData = JSON.parse(document.getElementById('opening-hours').value);
            var uniqueDaysInput = document.getElementById('unique-days');
            var rentDetailsInput = document.getElementById('rent-details');
            packageDetailSelect.disabled = true;
            dateSelect.disabled = true;
            var dayMapping = {
                0: 7,
                1: 1,
                2: 2,
                3: 3,
                4: 4,
                5: 5,
                6: 6
            };

            document.getElementById('date').addEventListener('change', function() {
                console.log('Date changed:', this.value);
                var dateParts = this.value.split('/');
                if (dateParts.length !== 3) {
                    console.error('Invalid date format:', this.value);
                    return;
                }

                var selectedDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                if (isNaN(selectedDate.getTime())) {
                    console.error('Invalid date:', this.value);
                    return;
                }

                var selectedDateString = selectedDate.getFullYear() + '-' +
                    String(selectedDate.getMonth() + 1).padStart(2, '0') + '-' +
                    String(selectedDate.getDate()).padStart(2, '0');
                var selectedVenueId = document.getElementById('venue-id').value;
                console.log('Selected date:', selectedDateString);
                console.log('Selected venue ID:', selectedVenueId);
            });

            function updateOpeningHours(openingHoursData) {
                console.log('updateOpeningHours called');
                var dateParts = dateInput.value.split('/');
                if (dateParts.length !== 3) {
                    console.error('Invalid date format:', dateInput.value);
                    return;
                }

                var selectedDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                if (isNaN(selectedDate.getTime())) {
                    console.error('Invalid date:', dateInput.value);
                    return;
                }
                var selectedDateString = selectedDate.getFullYear() + '-' +
                    String(selectedDate.getMonth() + 1).padStart(2, '0') + '-' +
                    String(selectedDate.getDate()).padStart(2, '0');
                var selectedDayIndex = selectedDate.getDay();
                var selectedDayId = dayMapping[selectedDayIndex];
                var selectedVenueId = venueIdInput.value;

                var filteredOpeningHours = openingHoursData.filter(function(openingHour) {
                    return openingHour.venue_id == selectedVenueId &&
                        openingHour.day_id == selectedDayId;
                });
                var bookedDates = JSON.parse(document.getElementById('book-dates').value);
                scheduleContainer.innerHTML = '';
                if (filteredOpeningHours.length === 0) {
                    var noScheduleAlert = document.createElement('div');
                    noScheduleAlert.classList.add('alert', 'alert-danger');
                    noScheduleAlert.textContent = 'Tidak ada jadwal venue';
                    scheduleContainer.appendChild(noScheduleAlert);
                    return;
                }
                var now = new Date();
                now.setMinutes(now.getMinutes() - 30);
                now.setSeconds(0, 0);
                filteredOpeningHours.forEach(function(openingHour) {
                    var hourText = openingHour.hour.hour;
                    var hourParts = hourText.split('.');
                    var hour = parseInt(hourParts[0], 10);
                    var minute = parseInt(hourParts[1], 10);
                    var scheduleTime = new Date(selectedDate);
                    scheduleTime.setHours(hour, minute, 0, 0);
                    console.log("Checking schedule:", hourText, "against current time:", now);
                    console.log("Schedule Time:", scheduleTime);

                    var isBooked = bookedDates.some(function(bookedDate) {
                        return bookedDate.opening_hour_id == openingHour.id && bookedDate.date ==
                            selectedDateString;
                    });
                    var badgeClass;
                    if (openingHour.status == 2) {
                        badgeClass = 'btn-primary';
                        if (isBooked) {
                            badgeClass = 'btn-danger';
                        }
                    } else {
                        badgeClass = isBooked ? 'btn-danger' : 'btn-secondary';
                    }
                    if (selectedDate.toDateString() === now.toDateString() && now > scheduleTime) {
                        console.log("Schedule is in the past for today, setting to btn-secondary.");
                        badgeClass = 'btn-secondary';
                    }

                    var labelElement = document.createElement('label');
                    labelElement.classList.add('btn', badgeClass, 'm-1', 'schedule-btn');
                    labelElement.textContent = hourText;
                    labelElement.style.cursor = 'pointer';
                    labelElement.style.width = '75px';
                    labelElement.style.height = '30px';
                    labelElement.style.lineHeight = '5px';

                    var inputElement = document.createElement('input');
                    inputElement.type = 'checkbox';
                    inputElement.name = 'opening_hours[]';
                    inputElement.value = openingHour.id;
                    inputElement.style.display = 'none';

                    if (openingHour.status == 1 || isBooked || (selectedDate.toDateString() === now
                            .toDateString() && now > scheduleTime)) {
                        labelElement.classList.add('btn-secondary-disabled');
                        labelElement.style.pointerEvents = 'none';
                        labelElement.style.opacity = '0.65';
                    } else {
                        labelElement.addEventListener('click', function() {
                            handleScheduleSelection(labelElement, inputElement, openingHour.id);
                        });
                    }
                    scheduleContainer.appendChild(inputElement);
                    scheduleContainer.appendChild(labelElement);
                });
            }

            function disableUnavailableDates(date) {
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                var uniqueDays = JSON.parse(uniqueDaysInput.value);
                var selectedDayId = dayMapping[date.getDay()];
                if (date.getTime() >= today.getTime() && uniqueDays.includes(selectedDayId)) {
                    return [true, "", ""];
                }
                return [false];
            }
            venueIdInput.addEventListener('change', function() {
                updateOpeningHours(openingHoursData);
            });
            if (dateInput) {
                $(dateInput).datepicker({
                    dateFormat: 'dd/mm/yy',
                    onSelect: function() {
                        console.log("Date selected:", dateInput.value);
                        dateInput.dispatchEvent(new Event('input'));
                    },
                    beforeShowDay: disableUnavailableDates
                });
            } else {
                console.error('Element with id "date" not found.');
            }
            dateInput.addEventListener('input', function(event) {
                console.log("Input event triggered");
                console.log("dateInput value:", this.value);

                var dateParts = this.value.split('/');
                if (dateParts.length !== 3) {
                    console.error('Invalid date format:', this.value);
                    return;
                }

                var selectedDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                if (isNaN(selectedDate.getTime())) {
                    console.error('Invalid date:', this.value);
                    return;
                }
                updateOpeningHours(openingHoursData);
            });

            function handleScheduleSelection(clickedLabel, clickedInput, clickedId) {
                var packageDetailSelect = document.getElementById('package_detail');
                console.log('packetDetail:', packageDetailSelect.value);
                var packageDetailId = packageDetailSelect.value;
                var packageDetail = packageDetails.find(detail => detail.id == packageDetailId);
                console.log('Package Detail:', packageDetail);
                if (!packageDetail) {
                    return;
                }
                var timeStatus = packageDetail.time_status;
                var slotsToSelect = timeStatus + 1;
                var allLabels = Array.from(document.querySelectorAll('label.schedule-btn'));
                var allInputs = Array.from(document.querySelectorAll('input[name="opening_hours[]"]'));
                var startIndex = allLabels.indexOf(clickedLabel);
                var endIndex = startIndex + slotsToSelect;
                allLabels.forEach(function(label) {
                    label.classList.remove('btn-success');
                });
                allInputs.forEach(function(input) {
                    input.checked = false;
                });
                if (endIndex > allLabels.length) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Jadwal tidak bisa dipilih',
                        text: 'Jadwal tidak bisa dipilih karena jadwal setelahnya tutup/telah dibooking'
                    });
                    return;
                }
                var validSelection = true;
                for (var i = startIndex; i < endIndex; i++) {
                    if (allLabels[i].classList.contains('btn-secondary-disabled')) {
                        validSelection = false;
                        break;
                    }
                }
                if (validSelection) {
                    for (var i = startIndex; i < endIndex; i++) {
                        allLabels[i].classList.add('btn-success');
                        allInputs[i].checked = true;
                    }
                    var startTime = allLabels[startIndex].textContent;
                    var endTimeParts = allLabels[endIndex - 1].textContent.split('.');
                    var endHour = parseInt(endTimeParts[0], 10);
                    var endMinute = parseInt(endTimeParts[1], 10) + 30;
                    if (endMinute >= 60) {
                        endHour += 1;
                        endMinute -= 60;
                    }
                    var endTime = endHour.toString().padStart(2, '0') + '.' + endMinute.toString().padStart(2, '0');
                    var detailScheduleContainer = document.getElementById('detail-schedule-container');
                    detailScheduleContainer.innerHTML = `
                    <h6 class="mb-2">
                        <span style="display: inline-block; width: 160px;">Jadwal Booking</span>:
                            <p style="display: inline-block; margin: 0; font-weight: normal;"> <div class="badge badge-primary ml-1"><i class="icon-copy dw dw-wall-clock2"></i> ${startTime}</div> - <div class="badge badge-primary"><i class="icon-copy dw dw-wall-clock2"></i> ${endTime}</div></p>
                        </h6>`;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Jadwal tidak bisa dipilih',
                        text: 'Jadwal tidak bisa dipilih karena jadwal setelahnya tutup/telah dibooking'
                    });
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var bookingForm = document.getElementById('bookingForm');

            bookingForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var requiredFields = [
                    document.getElementById('venue_id'),
                    document.getElementById('service'),
                    document.getElementById('package_detail'),
                    document.getElementById('date')
                ];

                var allValid = true;
                requiredFields.forEach(function(field) {
                    if (field.hasAttribute('disabled')) {
                        field.removeAttribute('disabled');
                        field.setAttribute('data-was-disabled', 'true');
                    }
                });
                requiredFields.forEach(function(field) {
                    if (!field.value) {
                        allValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (allValid) {
                    bookingForm.submit();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data tidak lengkap',
                        text: 'Harap lengkapi semua data sebelum menyimpan.'
                    });
                }
                requiredFields.forEach(function(field) {
                    if (field.getAttribute('data-was-disabled') === 'true') {
                        field.setAttribute('disabled', true);
                        field.removeAttribute('data-was-disabled');
                    }
                });
            });

            $('#bookingModal').on('hidden.bs.modal', function() {
                bookingForm.reset();
                var requiredFields = [
                    document.getElementById('service'),
                    document.getElementById('package_detail'),
                    document.getElementById('date')
                ];

                requiredFields.forEach(function(field) {
                    field.value = '';
                    field.classList.remove('is-invalid');
                });
                var packageDetailSelect = document.getElementById('package_detail');
                var dateSelect = document.getElementById('date');
                var scheduleContainer = document.getElementById('schedule-container');
                packageDetailSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
                packageDetailSelect.setAttribute('disabled', true);
                dateSelect.setAttribute('disabled', true);
                scheduleContainer.innerHTML =
                    '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';
            });
        });
    </script>
@endsection
@push('stylesheets')
    {{-- tombol katalog --}}
    <style>
        .btn-outline-info-custom:hover {
            background-color: #01bce2 !important;
            color: #fff !important;
            border-color: #01bce2 !important;
        }
    </style>
    {{-- tombol wa maps --}}
    <style>
        .whatsapp-link,
        .maps-link {
            position: fixed;
            z-index: 1000;
            color: white;
            border-radius: 50%;
            padding: 15px;
            width: 65px;
            /* Set a fixed width */
            height: 65px;
            /* Set a fixed height */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .whatsapp-link {
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
        }

        .maps-link {
            bottom: 100px;
            right: 20px;
            background-color: #007bff;
            /* Bootstrap info color */
        }

        .whatsapp-link:hover {
            transform: scale(1.1);
            background-color: #128C7E;
        }

        .maps-link:hover {
            transform: scale(1.1);
            background-color: #0056b3;
            /* Darker info color */
        }

        .whatsapp-link i,
        .maps-link i {
            font-size: 35px;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .whatsapp-link,
        .maps-link {
            animation: pulse 1.5s infinite;
        }
    </style>
    {{-- tombol katalog --}}
    <style>
        .nav-tabs .nav-link {
            position: relative;
            padding-bottom: 10px;
        }

        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 2px;
            background-color: #e27201;
            /* Warna garis */
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
                title: 'Berhasil Disalin',
                text: 'Nomor Rekening dari ' + paymentMethodName + ' telah Berhasil tersalin (No Rek : ' + text +
                    ').'
            });
        }

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    {{-- map salah --}}
    <script>
        function swalAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Link tidak valid',
                text: 'Link Lokasi dari Studio Foto tidak Valid',
                showConfirmButton: true
            });
        }
    </script>
    {{-- tooltip --}}
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    {{-- modal back login --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openDetailVenue = document.getElementById('openDetailVenue');

            if (openDetailVenue) { // Check if the element exists
                openDetailVenue.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Belum Login',
                        text: 'Silahkan untuk melakukan login atau register akun.',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Login',
                        confirmButtonColor: '#28a745',
                        cancelButtonText: 'Register',
                        cancelButtonColor: '#2843da',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('customer.login') }}";
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('customer.register') }}";
                        } else {
                            window.location.href =
                                "{{ route('customer.detail-venue-not-login', $venue->id) }}";
                        }
                    });
                });
            }
        });
    </script>
    <script>
        function openLink(event, url) {
            event.preventDefault();
            window.open(url, '_blank');
            return false;
        }
    </script>
@endpush
