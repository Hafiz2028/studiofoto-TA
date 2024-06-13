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

@endsection
@push('scripts')
    {{-- modal back login --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openDetailVenue = document.getElementById('openDetailVenue');

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
                    }
                });
            });
        });
    </script>
    <script>
        function openLink(event, url) {
            event.preventDefault();
            window.open(url, '_blank');
            return false;
        }
    </script>
    {{-- create booking --}}
    <script>
        var selectedPackageDetailId;
        var packages = @json($packages);
        var packageDetails = @json($packageDetails);
        var packages = @json($packages->load('addOnPackageDetails.addOnPackage'));

        function populateServiceEvents() {
            resetSelectAndDisable('service_event', 'Pilih Layanan...');
            resetSelectAndDisable('package', 'Pilih Paket Foto...');
            resetSelectAndDisable('package_detail', 'Pilih Jumlah Orang...');
            resetSelectAndDisable('print_photo_detail', 'Pilih Cetak Foto...');

            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            var serviceTypeId = document.getElementById('service_type').value;
            var venueId = document.getElementById('venue_id').value;
            var serviceEventSelect = document.getElementById('service_event');
            var url = `/api/cust/services/${serviceTypeId}?venue_id=${venueId}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    data.forEach(service => {
                        var option = document.createElement('option');
                        option.value = service.id;
                        option.text = service.name;
                        serviceEventSelect.appendChild(option);
                    });
                    enableSelect('service_event');
                })
                .catch(error => console.error('Error:', error));
        }

        function populateServicePackages() {
            resetSelectAndDisable('package', 'Pilih Paket Foto...');
            resetSelectAndDisable('package_detail', 'Pilih Jumlah Orang...');
            resetSelectAndDisable('print_photo_detail', 'Pilih Cetak Foto...');

            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            var serviceEventId = document.getElementById('service_event').value;
            var packageSelect = document.getElementById('package');
            var hasPackages = false;
            fetch(`/api/cust/packages/${serviceEventId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(package => {
                        var packageName = package.name;
                        var addOnText = '';

                        if (package.add_on_package_details && package.add_on_package_details.length > 0) {
                            package.add_on_package_details.forEach(function(addOnDetail, index) {
                                var addOnPackageName = addOnDetail.add_on_package.name;
                                var addOnSum = addOnDetail.sum;
                                addOnText += `(${addOnSum} ${addOnPackageName})`;

                                if (index < package.add_on_package_details.length - 1) {
                                    addOnText += ' + ';
                                }
                            });
                        }

                        if (addOnText) {
                            packageName += ' + ' + addOnText;
                        }

                        var option = document.createElement('option');
                        option.value = package.id;
                        option.text = packageName;
                        packageSelect.appendChild(option);
                        hasPackages = true;
                    });

                    if (hasPackages) {
                        enableSelect('package');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function populatePackageDetails() {
            resetSelectAndDisable('package_detail', 'Pilih Jumlah Orang...');
            resetSelectAndDisable('print_photo_detail', 'Pilih Cetak Foto...');
            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            var packageId = document.getElementById('package').value;
            var packageDetailSelect = document.getElementById('package_detail');
            var hasPackageDetails = false;
            fetch(`/api/cust/package-details/${packageId}`)
                .then(response => response.json())
                .then(packageDetails => {
                    packageDetails.forEach(packageDetail => {
                        var option = document.createElement('option');
                        option.value = packageDetail.id;
                        option.setAttribute('data-price', packageDetail.price);
                        option.text =
                            `${packageDetail.sum_person} Orang - Rp${formatCurrency(packageDetail.price)}`;
                        packageDetailSelect.appendChild(option);
                    });

                    if (packageDetails.length > 0) {
                        enableSelect('package_detail');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function enablePrintPhotoDetails() {
            var packageDetailId = document.getElementById('package_detail').value;
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var hiddenPrintPhotoDetailInput = document.getElementById('hidden_print_photo_detail_id');

            resetSelectAndDisable('print_photo_detail', 'Pilih Cetak Foto...');
            printPhotoDetailSelect.innerHTML = '<option value="0">Tidak Cetak Foto</option>';

            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            if (packageDetailId) {
                printPhotoDetailSelect.removeAttribute('disabled');
                selectedPackageDetailId = packageDetailId;
                var packageId = document.getElementById('package').value;
                fetch(`/api/cust/print-photo-details/${packageId}`)
                    .then(response => response.json())
                    .then(printPhotoDetails => {
                        printPhotoDetails.forEach(printPhotoDetail => {
                            var option = document.createElement('option');
                            option.value = printPhotoDetail.id;
                            option.setAttribute('data-size', printPhotoDetail.print_service_event.print_photo
                                .size);
                            option.setAttribute('data-price', printPhotoDetail.print_service_event.price);
                            option.text =
                                `Size ${printPhotoDetail.print_service_event.print_photo.size} - Rp ${formatCurrency(printPhotoDetail.print_service_event.price)}`;
                            printPhotoDetailSelect.appendChild(option);
                        });

                        updatePaymentMethodBadge();
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                printPhotoDetailSelect.setAttribute('disabled', true);
            }
            printPhotoDetailSelect.addEventListener('change', function() {
                hiddenPrintPhotoDetailInput.value = printPhotoDetailSelect.value === '0' ? '' :
                    printPhotoDetailSelect.value;
            });
        }

        function updatePaymentMethodBadge() {
            var badgeContainer = document.getElementById('badge-container');
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
            var serviceTypeSelect = document.getElementById('service_type');
            var serviceEventSelect = document.getElementById('service_event');
            var packageSelect = document.getElementById('package');
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var dateSelect = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');
            var dateInput = document.getElementById('date');
            var venueIdInput = document.getElementById('venue_id');
            var openingHoursData = JSON.parse(document.getElementById('opening-hours').value);
            var uniqueDaysInput = document.getElementById('unique-days');
            var packagePriceSpan = document.getElementById('package-price');
            var printPhotoPriceSpan = document.getElementById('print-photo-price');
            var totalPriceSpan = document.getElementById('total-price');
            var rentDetailsInput = document.getElementById('rent-details');
            packageDetailSelect.addEventListener('change', updatePrices);
            printPhotoDetailSelect.addEventListener('change', updatePrices);
            selectedPackageDetailId = null;
            serviceEventSelect.disabled = true;
            packageSelect.disabled = true;
            packageDetailSelect.disabled = true;
            printPhotoDetailSelect.disabled = true;
            dateSelect.disabled = true;

            function updatePrices() {
                var packagePrice = 0;
                var printPhotoPrice = 0;
                if (packageDetailSelect.value) {
                    var selectedPackageOption = packageDetailSelect.selectedOptions[0];
                    packagePrice = parseInt(selectedPackageOption.getAttribute('data-price')) || 0;
                }
                if (printPhotoDetailSelect.value && printPhotoDetailSelect.value !== 'no_print_photo') {
                    var selectedPrintPhotoOption = printPhotoDetailSelect.selectedOptions[0];
                    printPhotoPrice = parseInt(selectedPrintPhotoOption.getAttribute('data-price')) || 0;
                }
                packagePriceSpan.textContent = 'Rp ' + packagePrice.toLocaleString();
                printPhotoPriceSpan.textContent = 'Rp ' + printPhotoPrice.toLocaleString();
                var totalPrice = packagePrice + printPhotoPrice;
                totalPriceSpan.textContent = 'Rp ' + totalPrice.toLocaleString();

                document.getElementById('total-price-input').value = totalPrice;
            }
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
            });

            function updateOpeningHours(openingHoursData) {
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
                now.setSeconds(0, 0);
                console.log("Current Time:", now);
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

            function handleScheduleSelection(clickedLabel, clickedInput, clickedId) {
                console.log('handleScheduleSelection called with:', {
                    clickedLabel,
                    clickedInput,
                    clickedId
                });
                var packageDetail = packageDetails.find(function(detail) {
                    return detail.id == selectedPackageDetailId;
                });
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
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Jadwal tidak bisa dipilih',
                        text: 'Jadwal tidak bisa dipilih karena jadwal setelahnya tutup/telah dibooking'
                    });
                }
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

            function toggleDateInput() {
                var isPrintPhotoSelected = printPhotoDetailSelect.value !== '';
                var isPackageSelected = packageDetailSelect.value !== '';
                var isDisabled = !(isPrintPhotoSelected && isPackageSelected);
                dateInput.disabled = isDisabled;
                if (!isDisabled) {
                    $(dateInput).datepicker('enable');
                } else {
                    $(dateInput).datepicker('disable');
                }
                console.log("isPrintPhotoSelected:", isPrintPhotoSelected);
                console.log("isPackageSelected:", isPackageSelected);
                console.log("isDisabled:", isDisabled);
                if (isDisabled) {
                    scheduleContainer.innerHTML = '';
                    var infoAlert = document.createElement('div');
                    infoAlert.classList.add('alert', 'alert-info');
                    infoAlert.textContent = 'Belum memilih tanggal dan venue';
                    scheduleContainer.appendChild(infoAlert);
                }
            }

            venueIdInput.addEventListener('change', function() {
                updateOpeningHours(openingHoursData);
            });
            printPhotoDetailSelect.addEventListener('change', toggleDateInput);
            packageDetailSelect.addEventListener('change', toggleDateInput);
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
            toggleDateInput();
            updatePrices();
        });

        document.addEventListener('DOMContentLoaded', function() {
            var bookingForm = document.getElementById('bookingForm');

            bookingForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var requiredFields = [
                    document.getElementById('venue_id'),
                    document.getElementById('service_type'),
                    document.getElementById('service_event'),
                    document.getElementById('package'),
                    document.getElementById('package_detail'),
                    document.getElementById('print_photo_detail'),
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
                var serviceTypeSelect = document.getElementById('service_type');
                var serviceEventSelect = document.getElementById('service_event');
                var packageSelect = document.getElementById('package');
                var packageDetailSelect = document.getElementById('package_detail');
                var printPhotoDetailSelect = document.getElementById('print_photo_detail');
                var dateSelect = document.getElementById('date');
                var scheduleContainer = document.getElementById('schedule-container');


                serviceEventSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Layanan...</option>';
                packageSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
                packageDetailSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
                printPhotoDetailSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Cetak Foto...</option>';
                serviceEventSelect.setAttribute('disabled', true);
                packageSelect.setAttribute('disabled', true);
                packageDetailSelect.setAttribute('disabled', true);
                printPhotoDetailSelect.setAttribute('disabled', true);
                dateSelect.setAttribute('disabled', true);

                scheduleContainer.innerHTML =
                    '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';
            });
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            let venueId = $('#venue_id').val();
            $('#service_type').change(function() {
                let serviceTypeId = $(this).val();
                console.log("Selected serviceTypeId:", serviceTypeId);
                console.log("Using venueId:", venueId);
                resetSelects(['#service_event', '#package', '#package_detail', '#print_photo_detail']);

                if (serviceTypeId) {
                    $.get(`/api/cust/services/${serviceTypeId}`, {
                        venue_id: venueId
                    }, function(data) {
                        console.log("Service Events:", data);
                        $('#service_event').html(
                            '<option value="" disabled selected>Pilih Layanan...</option>');
                        data.forEach(service => {
                            console.log("Adding service to select:", service.name, service
                                .id);
                            if (!$('#service_event').find(`[value="${service.id}"]`)
                                .length) {
                                $('#service_event').append($('<option>', {
                                    value: service.id,
                                    text: service.name
                                }));
                            }
                        });
                        console.log($('#service_event').html());
                    }).fail(function() {
                        console.log("Failed to fetch service events.");
                    });
                }
            });

            $('#service_event').change(function() {
                let serviceEventId = $(this).val();
                resetSelects(['#package', '#package_detail', '#print_photo_detail']);
                if (serviceEventId) {
                    $.get(`/api/cust/packages/${serviceEventId}`, function(data) {
                        console.log("Packages:", data);
                        let $select = $('#package');
                        $select.empty().append(
                            '<option value="" disabled selected>Pilih Paket Foto...</option>');
                        data.forEach(package => {
                            let optionText = `${package.name}`;
                            if (package.addOnPackageDetails) {
                                package.addOnPackageDetails.forEach(detail => {
                                    optionText +=
                                        ` - ${detail.addOnPackage.name} (${detail.sum})`;
                                });
                            }
                            $select.append(new Option(optionText, package.id));
                        });
                    }).fail(function() {
                        console.log("Failed to fetch packages.");
                    });;
                }
            });

            $('#package').change(function() {
                let packageId = $(this).val();
                resetSelects(['#package_detail', '#print_photo_detail']);
                if (packageId) {
                    $.get(`/api/cust/package-details/${packageId}`, function(data) {
                        console.log("Package Details:", data);
                        let $select = $('#package_detail');
                        $select.empty().append(
                            '<option value="" disabled selected>Pilih Jumlah Orang...</option>');
                        data.forEach(detail => {
                            let optionText =
                                `${detail.sum_person} Orang - Rp ${number_format(detail.price, 0, 0)}`;
                            $select.append(new Option(optionText, detail.id));
                        });

                        $.get(`/api/cust/print-photo-details/${packageId}`, function(data) {
                            console.log("Print Photo Details:", data);
                            let $select = $('#print_photo_detail');
                            $select.empty().append(
                                '<option value="" disabled selected>Pilih Cetak Foto...</option>'
                            );
                            data.forEach(printPhotoDetail => {
                                let optionText =
                                    `Size ${printPhotoDetail.printServiceEvent.printPhoto.size} - Rp ${number_format(printPhotoDetail.printServiceEvent.price, 0, 0)}`;
                                $select.append(new Option(optionText,
                                    printPhotoDetail.id));
                            });
                        });
                    }).fail(function() {
                        console.log("Failed to fetch packages.");
                    });;
                }
            });

            function resetSelects(selectIds) {
                selectIds.forEach(id => {
                    $(id).empty().append('<option value="" disabled selected>Pilih...</option>');
                });
            }

            function number_format(number, decimals, decPoint, thousandsSep) {
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                let n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep,
                    dec = (typeof decPoint === 'undefined') ? '.' : decPoint,
                    s = '',
                    toFixedFix = function(n, prec) {
                        let k = Math.pow(10, prec);
                        return '' + (Math.round(n * k) / k).toFixed(prec);
                    };
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }

            document.addEventListener('touchstart', function() {}, {
                passive: true
            });
        });
    </script> --}}
@endpush
