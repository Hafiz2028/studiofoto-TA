@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Booking List')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Booking List</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Booking List
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
                            <h4 class="h4 text">List Booking Studio <span
                                    class="text-primary">{{ ucfirst(Auth::user()->name) }}</span></h4>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bookingModal">
                                <i class="fa fa-plus"></i> Offline Booking
                            </button>
                            @include('back.pages.owner.booking-manage.create')
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-borderless table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Venue</th>
                                    <th>Tipe Layanan</th>
                                    <th>Paket Foto</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="sortable_services">
                                @if (!$rents->count() > 0)
                                    <tr>
                                        <td colspan="7">
                                            <div class="alert alert-info text-center">Tidak Ada Venue yang Dibooking.</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($rents as $rent)
                                        <td>{{ $rent->date }}</td>
                                        <td>{{ $rent->name }}</td>
                                        <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
                                        </td>
                                        <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->serviceType->service_name }}
                                        </td>
                                        <td>{{ $rent->servicePackageDetail->servicePackage->name }}</td>
                                        <td>{{ $rent->formatted_schedule }}</td>
                                        <td>
                                            @if ($rent->rent_status == 0)
                                                <span class="badge badge-info ">Diajukan</span>
                                            @elseif ($rent->rent_status == 1)
                                                <span class="badge badge-success ">Dibooking</span>
                                            @elseif ($rent->rent_status == 2)
                                                <span class="badge badge-primary ">Selesai</span>
                                            @elseif ($rent->rent_status == 3)
                                                <span class="badge badge-danger ">Ditolak</span>
                                            @elseif ($rent->rent_status == 4)
                                                <span class="badge badge-warning ">Expired</span>
                                            @elseif ($rent->rent_status == 5)
                                                <span class="badge badge-warning ">Belum Bayar</span>
                                            @else
                                                <span class="badge badge-danger ">Tidak Valid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rent->rent_status == 0)
                                                <a href="" class="btn btn-outline-info" data-toggle="tooltip"
                                                    data-placement="auto" title="Detail Booking"><i
                                                        class="fas fa-info"></i></a>
                                                <a href="" class="btn btn-success" data-toggle="modal"
                                                    data-target=""><i class="fas fa-check"></i></a>
                                                <a href="" class="btn btn-danger" data-toggle="modal"
                                                    data-target=""><i class="bi bi-x-lg"></i></a>
                                            @elseif ($rent->rent_status == 1 || $rent->rent_status == 2)
                                                <a href="" class="btn btn-outline-info" data-toggle="tooltip"
                                                    data-placement="auto" title="Detail Booking"><i
                                                        class="fas fa-info"></i></a>
                                                <a href="" class="btn btn-outline-secondary" data-toggle="tooltip"
                                                    data-placement="auto" title="Pembayaran"><i
                                                        class="fas fa-money"></i></a>
                                                <a href="" class="btn btn-outline-primary" data-toggle="tooltip"
                                                    data-placement="auto" title="Edit Booking"><i
                                                        class="fas fa-edit"></i></a>
                                                <a href="" class="btn btn-outline-danger" data-toggle="tooltip"
                                                    data-placement="auto" title="Hapus Booking"><i
                                                        class="fas fa-trash"></i></a>
                                            @elseif ($rent->rent_status == 5)
                                                <a href="" class="btn btn-outline-info" data-toggle="tooltip"
                                                    data-placement="auto" title="Detail Booking"><i
                                                        class="fas fa-info"></i></a>
                                                <a href="{{route('owner.booking.show-payment', ['booking' => $rent->id])}}" class="btn btn-outline-secondary" data-toggle="tooltip"
                                                    data-placement="auto" title="Pembayaran"><i
                                                        class="fas fa-money"></i></a>
                                                <a href="" class="btn btn-outline-primary" data-toggle="tooltip"
                                                    data-placement="auto" title="Edit Booking"><i
                                                        class="fas fa-edit"></i></a>
                                                <a href="" class="btn btn-outline-danger" data-toggle="tooltip"
                                                    data-placement="auto" title="Hapus Booking"><i
                                                        class="fas fa-trash"></i></a>
                                            @elseif ($rent->rent_status == 3 || $rent->rent_status == 4)
                                                <a href="" class="btn btn-info"><i class="fas fa-info"></i></a>
                                            @else
                                                <div class="alert alert-danger">Tidak Valid</div>
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('styles')
    <style>
        .schedule-btn {
            display: inline-block;
            width: 200px;
            height: 60px;
            line-height: 60px;
            text-align: center;
            margin: 5px;
            vertical-align: middle;
        }

        .btn-secondary-disabled {
            background-color: #6c757d;
            opacity: 0.65;
            pointer-events: none;
            cursor: not-allowed;
        }

        .btn-success {
            background-color: #28a745 !important;
            color: white !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        var selectedPackageDetailId;
        var packages = @json($packages);
        var packageDetails = @json($packageDetails);
        var packages = @json($packages->load('addOnPackageDetails.addOnPackage'));

        function populateServiceTypes() {
            var venueId = document.getElementById('venue_id').value;
            var serviceTypeSelect = document.getElementById('service_type');
            var serviceEventSelect = document.getElementById('service_event');
            var packageSelect = document.getElementById('package');
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var dateSelect = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');

            serviceTypeSelect.innerHTML = '<option value="" disabled selected>Pilih Tipe Layanan...</option>';
            serviceEventSelect.innerHTML = '<option value="" disabled selected>Pilih Layanan...</option>';
            packageSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            serviceTypeSelect.setAttribute('disabled', true);
            serviceEventSelect.setAttribute('disabled', true);
            packageSelect.setAttribute('disabled', true);
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.setAttribute('disabled', true);

            // Reset date input and schedule container
            dateSelect.value = '';
            dateSelect.setAttribute('disabled', true);
            scheduleContainer.innerHTML = '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            var hasServiceType = false;
            @foreach ($services as $service)
                if ({{ $service->venue_id }} == venueId) {
                    var option = document.createElement('option');
                    option.value = "{{ $service->service_type_id }}";
                    option.text = "{{ $service->serviceType->service_name }}";
                    serviceTypeSelect.appendChild(option);
                    hasServiceType = true;
                }
            @endforeach
            if (hasServiceType) {
                serviceTypeSelect.removeAttribute('disabled');
            }
        }

        function populateServiceEvents() {
            var serviceTypeId = document.getElementById('service_type').value;
            var venueId = document.getElementById('venue_id').value;
            var serviceEventSelect = document.getElementById('service_event');
            var packageSelect = document.getElementById('package');
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var dateSelect = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');

            serviceEventSelect.innerHTML = '<option value="" disabled selected>Pilih Layanan...</option>';
            serviceEventSelect.setAttribute('disabled', true);
            packageSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
            packageSelect.setAttribute('disabled', true);
            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            printPhotoDetailSelect.setAttribute('disabled', true);


            // Reset date input and schedule container
            dateSelect.value = '';
            dateSelect.setAttribute('disabled', true);
            scheduleContainer.innerHTML = '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            var hasService = false;
            @foreach ($services as $service)
                if ({{ $service->service_type_id }} == serviceTypeId && {{ $service->venue_id }} == venueId) {
                    var option = document.createElement('option');
                    option.value = "{{ $service->id }}";
                    option.text = "{{ $service->name }}";
                    serviceEventSelect.appendChild(option);
                    hasService = true;
                }
            @endforeach
            if (hasService) {
                serviceEventSelect.removeAttribute('disabled');
            }
        }

        function populateServicePackages() {
            var serviceEventId = document.getElementById('service_event').value;
            var packageSelect = document.getElementById('package');
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var dateSelect = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');

            packageSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
            packageSelect.setAttribute('disabled', true);
            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            printPhotoDetailSelect.setAttribute('disabled', true);

            // Reset date input and schedule container
            dateSelect.value = '';
            dateSelect.setAttribute('disabled', true);
            scheduleContainer.innerHTML = '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            var hasPackages = false;
            @foreach ($packages as $package)
                if ({{ $package->service_event_id }} == serviceEventId) {
                    var addOnDetails = @json($package->addOnPackageDetails);
                    var packageName = "{{ $package->name }}";
                    var addOnText = '';
                    if (addOnDetails.length > 0) {
                        addOnDetails.forEach(function(addOnDetail, index) {
                            var addOnPackageName = addOnDetail.add_on_package.name;
                            var addOnSum = addOnDetail.sum;
                            addOnText += `(${addOnSum} ${addOnPackageName})`;
                            if (index < addOnDetails.length - 1) {
                                addOnText += ' + ';
                            }
                        });
                        packageName += ' + ' + addOnText;
                    }
                    var option = document.createElement('option');
                    option.value = "{{ $package->id }}";
                    option.text = packageName;
                    packageSelect.appendChild(option);
                    hasPackages = true;
                }
            @endforeach
            if (hasPackages) {
                packageSelect.removeAttribute('disabled');
            }
        }

        function populatePackageDetails() {
            var packageId = document.getElementById('package').value;
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var dateSelect = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');

            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            printPhotoDetailSelect.setAttribute('disabled', true);

            // Reset date input and schedule container
            dateSelect.value = '';
            dateSelect.setAttribute('disabled', true);
            scheduleContainer.innerHTML = '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            var hasPackageDetails = false;
            @foreach ($packageDetails as $packageDetail)
                if ({{ $packageDetail->service_package_id }} == packageId) {
                    var option = document.createElement('option');
                    option.value = "{{ $packageDetail->id }}";
                    option.setAttribute('data-price', "{{ $packageDetail->price }}");
                    option.text =
                        "{{ $packageDetail->sum_person }} Orang - Rp{{ number_format($packageDetail->price, 0, ',', '.') }}";
                    packageDetailSelect.appendChild(option);
                    hasPackageDetails = true;
                }
            @endforeach
            if (hasPackageDetails) {
                packageDetailSelect.removeAttribute('disabled');
            }
        }

        function enablePrintPhotoDetails() {
            var packageDetailId = document.getElementById('package_detail').value;
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var hiddenPrintPhotoDetailInput = document.getElementById('hidden_print_photo_detail_id');

            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            var dateSelect = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');

            var noPrintPhotoOption = document.createElement('option');
            noPrintPhotoOption.value = "no_print_photo";
            noPrintPhotoOption.text = "Tidak Cetak Foto";
            printPhotoDetailSelect.appendChild(noPrintPhotoOption);
            // Reset date input and schedule container
            dateSelect.value = '';
            dateSelect.setAttribute('disabled', true);
            scheduleContainer.innerHTML = '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';

            if (packageDetailId) {
                printPhotoDetailSelect.removeAttribute('disabled');
                selectedPackageDetailId = packageDetailId;
                var packageId = document.getElementById('package').value;
                @foreach ($packages as $package)
                    if ({{ $package->id }} == packageId) {
                        @foreach ($package->printPhotoDetails as $printPhotoDetail)
                            var option = document.createElement('option');
                            option.value = "{{ $printPhotoDetail->id }}";
                            option.setAttribute('data-size',
                                "{{ $printPhotoDetail->printServiceEvent->printPhoto->size }}");
                            option.setAttribute('data-price', "{{ $printPhotoDetail->printServiceEvent->price }}");
                            option.text =
                                "Size {{ $printPhotoDetail->printServiceEvent->printPhoto->size }} - Rp {{ number_format($printPhotoDetail->printServiceEvent->price, 0, ',', '.') }}";
                            printPhotoDetailSelect.appendChild(option);
                        @endforeach
                    }
                @endforeach
                updatePaymentMethodBadge();
            } else {
                printPhotoDetailSelect.setAttribute('disabled', true);
            }
            printPhotoDetailSelect.addEventListener('change', function() {
                if (printPhotoDetailSelect.value === 'no_print_photo') {
                    hiddenPrintPhotoDetailInput.value = null;
                } else {
                    hiddenPrintPhotoDetailInput.value = printPhotoDetailSelect.value;
                }
            });
        }

        function updatePaymentMethodBadge() {
            var badgeContainer = document.getElementById('badge-container');
            if (selectedPackageDetailId) {
                var packageDetail = packageDetails.find(function(detail) {
                    return detail.id == selectedPackageDetailId;
                });
                if (packageDetail) {
                    var timeStatus = packageDetail.time_status;
                    var packageId = packageDetail.service_package_id;
                    var selectedPackage = packages.find(function(pkg) {
                        return pkg.id == packageId;
                    });
                    if (!selectedPackage) {
                        console.error('Package not found for packageDetailId:', selectedPackageDetailId);
                        return;
                    }
                    var dpStatus = selectedPackage.dp_status;
                    var dpPercentage = selectedPackage.dp_percentage;
                    var dpMin = selectedPackage.dp_min;
                    while (badgeContainer.firstChild) {
                        badgeContainer.removeChild(badgeContainer.firstChild);
                    }
                    var timeBadge = document.createElement('div');
                    timeBadge.classList.add('badge', 'mb-1');
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
                    if (dpStatus == 0) {
                        paymentBadge.classList.add('badge-info');
                        paymentBadge.textContent = 'Hanya Lunas';
                    } else if (dpStatus == 1) {
                        paymentBadge.classList.add('badge-success');
                        paymentBadge.textContent = 'DP Minimal ' + (dpPercentage * 100) + '%';
                    } else if (dpStatus == 2) {
                        paymentBadge.classList.add('badge-success');
                        var minimalPayment = Math.round(dpMin / 1000) * 1000;
                        var minPayment = minimalPayment.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                        paymentBadge.textContent = 'Min. Bayar ' + minPayment;
                    } else {
                        paymentBadge.classList.add('badge-danger');
                        paymentBadge.textContent = 'Tidak Ada Metode Pembayaran';
                    }
                    badgeContainer.appendChild(paymentBadge);
                }
            }
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
            var scheduleContainer = document.getElementById('schedule-container');
            var venueIdInput = document.getElementById('venue_id');
            var openingHoursData = JSON.parse(document.getElementById('opening-hours').value);
            var uniqueDaysInput = document.getElementById('unique-days');

            //update prices
            var packagePriceSpan = document.getElementById('package-price');
            var printPhotoPriceSpan = document.getElementById('print-photo-price');
            var totalPriceSpan = document.getElementById('total-price');
            packageDetailSelect.addEventListener('change', updatePrices);
            printPhotoDetailSelect.addEventListener('change', updatePrices);
            //end update prices

            selectedPackageDetailId = null;
            serviceTypeSelect.disabled = true;
            serviceEventSelect.disabled = true;
            packageSelect.disabled = true;
            packageDetailSelect.disabled = true;
            printPhotoDetailSelect.disabled = true;
            dateSelect.disabled = true;
            //update prices
            function updatePrices() {
                var packagePrice = 0;
                var printPhotoPrice = 0;

                // Get selected package price
                if (packageDetailSelect.value) {
                    var selectedPackageOption = packageDetailSelect.selectedOptions[0];
                    packagePrice = parseInt(selectedPackageOption.getAttribute('data-price')) || 0;
                }

                // Get selected print photo price if not "Tidak Cetak Foto"
                if (printPhotoDetailSelect.value && printPhotoDetailSelect.value !== 'no_print_photo') {
                    var selectedPrintPhotoOption = printPhotoDetailSelect.selectedOptions[0];
                    printPhotoPrice = parseInt(selectedPrintPhotoOption.getAttribute('data-price')) || 0;
                }

                // Update displayed prices
                packagePriceSpan.textContent = 'Rp ' + packagePrice.toLocaleString();
                printPhotoPriceSpan.textContent = 'Rp ' + printPhotoPrice.toLocaleString();

                // Calculate and display total price
                var totalPrice = packagePrice + printPhotoPrice;
                totalPriceSpan.textContent = 'Rp ' + totalPrice.toLocaleString();

                document.getElementById('total-price-input').value = totalPrice;
            }
            //update prices


            var dayMapping = {
                0: 7, // Sunday -> 7 (Minggu)
                1: 1, // Monday -> 1 (Senin)
                2: 2, // Tuesday -> 2 (Selasa)
                3: 3, // Wednesday -> 3 (Rabu)
                4: 4, // Thursday -> 4 (Kamis)
                5: 5, // Friday -> 5 (Jumat)
                6: 6 // Saturday -> 6 (Sabtu)
            };

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

                var selectedDayIndex = selectedDate.getDay();
                var selectedDayId = dayMapping[selectedDayIndex];
                var selectedVenueId = venueIdInput.value;

                var filteredOpeningHours = openingHoursData.filter(function(openingHour) {
                    return openingHour.venue_id == selectedVenueId &&
                        openingHour.day_id == selectedDayId;
                });

                console.log("selectedDate:", selectedDate);
                console.log("selectedDayIndex:", selectedDayIndex);
                console.log("selectedDayId:", selectedDayId);
                console.log("filteredOpeningHours:", filteredOpeningHours);

                scheduleContainer.innerHTML = '';
                if (filteredOpeningHours.length === 0) {
                    var noScheduleAlert = document.createElement('div');
                    noScheduleAlert.classList.add('alert', 'alert-danger');
                    noScheduleAlert.textContent = 'Tidak ada jadwal venue';
                    scheduleContainer.appendChild(noScheduleAlert);
                    return;
                }
                filteredOpeningHours.forEach(function(openingHour) {
                    var hourText = openingHour.hour.hour;
                    var badgeClass = openingHour.status == 2 ? 'btn-primary' : 'btn-secondary';

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

                    if (openingHour.status == 1) {
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

                serviceTypeSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Tipe Layanan...</option>';
                serviceEventSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Layanan...</option>';
                packageSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
                packageDetailSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
                printPhotoDetailSelect.innerHTML =
                    '<option value="" disabled selected>Pilih Cetak Foto...</option>';
                serviceTypeSelect.setAttribute('disabled', true);
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
@endpush
