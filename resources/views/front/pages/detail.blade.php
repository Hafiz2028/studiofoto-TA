@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Foto Yuk | Detail Venue')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/front/img/breadcrumb.jpg">
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
                        <h3>{{ $venue->name }}</h3>
                        <div class="product__details__price">
                            @if ($minPrice == 0 && $maxPrice == 0)
                                Tidak ada paket foto
                            @else
                                Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp
                                {{ number_format($maxPrice, 0, ',', '.') }}
                            @endif
                        </div>
                        <p>{{ $venue->information }}</p>
                        <div>
                            <a href="" class="primary-btn">PAKET
                                HARGA</a>
                        </div>
                        <div>
                            <a href="https://wa.me/{{ $venue->phone_number }}?text={{ urlencode('Halo, saya ingin booking jadwal studio foto.') }}"
                                target="_blank" data-toogle="tooltip" title="Chat pihak Studio Foto" data-placement="auto"
                                class="primary-btn"><i class="fab fa-whatsapp" style="font-size:10mm;"></i></a>
                            @if (auth()->guard('customer')->check())
                                <a href="{{ route('customer.booking.create') }}" class="primary-btn" data-toggle="modal"
                                    data-target="#bookingModal">BOOKING</a>
                                @include('back.pages.owner.booking-manage.create')
                            @else
                                <a href="javascript:;" id="openDetailVenue" class="primary-btn" data-toggle="modal"
                                    data-target="#openDetailVenue">BOOKING</a>
                            @endif
                        </div>
                        <ul>
                            <li><b>Availability</b> <span>In Stock</span></li>
                            <li><b>Shipping</b> <span>01 day shipping. <samp>Free pickup today</samp></span></li>
                            <li><b>Weight</b> <span>0.5 kg</span></li>
                            <li><b>Share on</b>
                                <div class="share">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                                    aria-selected="false">Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                    aria-selected="false">Reviews <span>(1)</span></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Products Infomation</h6>
                                    <p>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.
                                        Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus. Vivamus
                                        suscipit tortor eget felis porttitor volutpat. Vestibulum ac diam sit amet quam
                                        vehicula elementum sed sit amet dui. Donec rutrum congue leo eget malesuada.
                                        Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur arcu erat,
                                        accumsan id imperdiet et, porttitor at sem. Praesent sapien massa, convallis a
                                        pellentesque nec, egestas non nisi. Vestibulum ac diam sit amet quam vehicula
                                        elementum sed sit amet dui. Vestibulum ante ipsum primis in faucibus orci luctus
                                        et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam
                                        vel, ullamcorper sit amet ligula. Proin eget tortor risus.</p>
                                    <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Lorem
                                        ipsum dolor sit amet, consectetur adipiscing elit. Mauris blandit aliquet
                                        elit, eget tincidunt nibh pulvinar a. Cras ultricies ligula sed magna dictum
                                        porta. Cras ultricies ligula sed magna dictum porta. Sed porttitor lectus
                                        nibh. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.
                                        Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Sed
                                        porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum
                                        sed sit amet dui. Proin eget tortor risus.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Products Infomation</h6>
                                    <p>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.
                                        Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus.
                                        Vivamus suscipit tortor eget felis porttitor volutpat. Vestibulum ac diam
                                        sit amet quam vehicula elementum sed sit amet dui. Donec rutrum congue leo
                                        eget malesuada. Vivamus suscipit tortor eget felis porttitor volutpat.
                                        Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Praesent
                                        sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ac
                                        diam sit amet quam vehicula elementum sed sit amet dui. Vestibulum ante
                                        ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
                                        Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula.
                                        Proin eget tortor risus.</p>
                                    <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Lorem
                                        ipsum dolor sit amet, consectetur adipiscing elit. Mauris blandit aliquet
                                        elit, eget tincidunt nibh pulvinar a. Cras ultricies ligula sed magna dictum
                                        porta. Cras ultricies ligula sed magna dictum porta. Sed porttitor lectus
                                        nibh. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Products Infomation</h6>
                                    <p>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.
                                        Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus.
                                        Vivamus suscipit tortor eget felis porttitor volutpat. Vestibulum ac diam
                                        sit amet quam vehicula elementum sed sit amet dui. Donec rutrum congue leo
                                        eget malesuada. Vivamus suscipit tortor eget felis porttitor volutpat.
                                        Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Praesent
                                        sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ac
                                        diam sit amet quam vehicula elementum sed sit amet dui. Vestibulum ante
                                        ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
                                        Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula.
                                        Proin eget tortor risus.</p>
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
                        <h2>Related Product (tipe layanan sama)</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="/front/img/product/product-1.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="/front/img/product/product-2.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="/front/img/product/product-3.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="/front/img/product/product-7.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
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
                    document.getElementById('venue_id'),
                    document.getElementById('service'),
                    document.getElementById('package_detail'),
                    document.getElementById('date')
                ];

                requiredFields.forEach(function(field) {
                    field.value = '';
                    field.classList.remove('is-invalid');
                });
                var serviceEventSelect = document.getElementById('service');
                var packageDetailSelect = document.getElementById('package_detail');
                var dateSelect = document.getElementById('date');
                var scheduleContainer = document.getElementById('schedule-container');
                serviceEventSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Layanan...</option>';
                packageDetailSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Jumlah Orang...</option>';

                serviceEventSelect.setAttribute('disabled', true);
                packageDetailSelect.setAttribute('disabled', true);
                dateSelect.setAttribute('disabled', true);

                scheduleContainer.innerHTML =
                    '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';
            });
        });
    </script>
@endsection
@push('scripts')
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
