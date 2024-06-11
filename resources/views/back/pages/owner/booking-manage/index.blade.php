@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Booking List')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Booking List</h4>
                </div>
                <nav aria-label="breadcrumb " role="navigation">
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
                            <button type="button" class="btn btn-primary exclude-alert" data-toggle="modal"
                                data-target="#bookingModal">
                                <i class="fa fa-plus"></i> Offline Booking
                            </button>
                            @include('back.pages.owner.booking-manage.create')
                            <a href="javascript:void(0);" id="refresh-schedule" type="button" class="btn btn-outline-info"
                                data-toggle="tooltip" title="Cek Jadwal yang Expired"><i
                                    class="icon-copy dw dw-wall-clock2"></i> Refresh
                                Jadwal</a>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-borderless table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Venue</th>
                                    <th>Paket Foto</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
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
                                        <tr id="rent-{{ $rent->id }}">
                                            <td>{{ $rent->date }}</td>
                                            <td>{{ $rent->name }}
                                            </td>
                                            <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
                                            </td>
                                            <td>{{ $rent->servicePackageDetail->servicePackage->name }}
                                                @if ($rent->servicePackageDetail->servicePackage->addOnPackageDetails->isNotEmpty())
                                                    @foreach ($rent->servicePackageDetail->servicePackage->addOnPackageDetails as $addOnPackageDetail)
                                                        + ({{ $addOnPackageDetail->sum }}
                                                        {{ $addOnPackageDetail->addOnPackage->name }})
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if ($rent->formatted_schedule == null)
                                                    <div class="badge badge-danger">Tidak ada</div>
                                                @else
                                                    {{ $rent->formatted_schedule }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($rent->formatted_schedule == null)
                                                    <span class="badge badge-danger">Jadwal Salah</span>
                                                @else
                                                    @if ($rent->rent_status == 0)
                                                        <span class="badge badge-info ">Diajukan</span>
                                                    @elseif ($rent->rent_status == 1)
                                                        <span class="badge badge-success ">Dibooking</span>
                                                    @elseif ($rent->rent_status == 2)
                                                        <span class="badge badge-primary ">Selesai</span>
                                                    @elseif ($rent->rent_status == 3)
                                                        <span class="badge badge-danger ">Ditolak</span>
                                                    @elseif ($rent->rent_status == 4)
                                                        <span class="badge badge-secondary ">Expired</span>
                                                    @elseif ($rent->rent_status == 5)
                                                        <span class="badge badge-warning ">Belum Bayar</span>
                                                    @elseif ($rent->rent_status == 6)
                                                        <span class="badge badge-dark ">Sedang Foto</span>
                                                    @else
                                                        <span class="badge badge-danger ">Tidak Valid</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($rent->rent_status == 4)
                                                    <span class="badge badge-secondary ">Expired</span>
                                                @else
                                                    @if ($rent->dp_price == $rent->total_price)
                                                        <span class="badge badge-success">Lunas</span>
                                                    @elseif ($rent->dp_price < $rent->total_price && $rent->dp_price != null)
                                                        <span class="badge badge-info ">Dp (Rp
                                                            {{ number_format($rent->dp_price) }})</span>
                                                    @elseif ($rent->dp_price == null)
                                                        <span class="badge badge-warning ">Belum Bayar</span>
                                                    @else
                                                        <span class="badge badge-danger ">Tidak Valid</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($rent->rent_status == 0)
                                                    <a href="" class="btn btn-outline-info" data-toggle="tooltip"
                                                        data-placement="auto" title="Detail Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-info"></i></a>
                                                    <a href="" class="btn btn-success" data-toggle="modal"
                                                        data-target=""
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-check"></i></a>
                                                    <a href="" class="btn btn-danger exclude-alert"
                                                        data-toggle="modal" data-target=""
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="bi bi-x-lg"></i></a>
                                                @elseif ($rent->rent_status == 1 || $rent->rent_status == 2)
                                                    <a href="{{ route('owner.booking.show', $rent->id) }}"
                                                        class="btn btn-outline-info" data-toggle="tooltip"
                                                        data-placement="auto" title="Detail Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-info"></i></a>
                                                    <a href="{{ route('owner.booking.edit', $rent->id) }}"
                                                        class="btn btn-outline-primary" data-toggle="tooltip"
                                                        data-placement="auto" title="Edit Jadwal">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="" class="btn btn-outline-danger exclude-alert"
                                                        data-toggle="tooltip" data-placement="auto" title="Hapus Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-trash"></i></a>
                                                @elseif ($rent->rent_status == 5)
                                                    <a href="" class="btn btn-outline-info" data-toggle="tooltip"
                                                        data-placement="auto" title="Detail Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-info"></i></a>
                                                    <a href="{{ route('owner.booking.show-payment', ['booking' => $rent->id]) }}"
                                                        class="btn btn-outline-secondary" data-toggle="tooltip"
                                                        data-placement="auto" title="Pembayaran"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-money"></i></a>
                                                    <a href="{{ route('owner.booking.edit', $rent->id) }}"
                                                        class="btn btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="" class="btn btn-outline-danger exclude-alert"
                                                        data-toggle="tooltip" data-placement="auto" title="Hapus Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-trash"></i></a>
                                                @elseif ($rent->rent_status == 3 || $rent->rent_status == 4)
                                                    <a href="{{ route('owner.booking.show', $rent->id) }}"
                                                        class="btn btn-outline-info" data-toggle="tooltip"
                                                        data-placement="auto" title="Detail Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="fas fa-info"></i></a>
                                                @else
                                                    <div class="alert alert-danger">Tidak Valid</div>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- @include('back.pages.owner.booking-manage.edit') --}}
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
    {{-- cek jadwal expired --}}
    <script>
        document.getElementById('refresh-schedule').addEventListener('click', function() {
            $.ajax({
                url: '{{ route('owner.booking.update-status') }}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status === 'success') {
                        console.log(data.message);
                        console.log(data.openingHour);
                        console.log(data.scheduleTime);
                        console.log(data.cutoffTime);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Jadwal Expired',
                            html: data.message.join('<br>')
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        console.log(data.message);
                        console.log(data.openingHour);
                        console.log(data.scheduleTime);
                        console.log(data.cutoffTime);
                        Swal.fire({
                            icon: 'success',
                            title: 'Jadwal Expired',
                            text: data.message
                        });
                    }
                },
                error: function() {
                    console.log('Something went wrong!');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                }
            });
        });
    </script>

    {{-- jadwal salah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn:not(.btn-outline-primary):not(.exclude-alert)');
            buttons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const schedule = button.getAttribute('data-schedule');
                    if (schedule === 'null') {
                        event.preventDefault();
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terdapat kesalahan pada Jadwal Booking.',
                            icon: 'error',
                            confirmButtonText: 'Perbarui Jadwal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const rentId = button.closest('tr').getAttribute('id')
                                    .replace('rent-', '');
                                $(`#editBookingModal${rentId}`).modal('show');
                            }
                        });
                    }
                });
            });
        });
    </script>
    {{-- create booking --}}
    <script>
        var selectedPackageDetailId;
        var packages = @json($packages);
        var packageDetails = @json($packageDetails);
        var packages = @json($packages->load('addOnPackageDetails.addOnPackage'));

        function populateServiceTypes() {
            resetSelectAndDisable('service_type', 'Pilih Tipe Layanan...');
            resetSelectAndDisable('service_event', 'Pilih Layanan...');
            resetSelectAndDisable('package', 'Pilih Paket Foto...');
            resetSelectAndDisable('package_detail', 'Pilih Jumlah Orang...');
            resetSelectAndDisable('print_photo_detail', 'Pilih Cetak Foto...');
            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';
            var venueId = document.getElementById('venue_id').value;
            var serviceTypeSelect = document.getElementById('service_type');

            fetch(`/api/services/${venueId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(service => {
                        var option = document.createElement('option');
                        option.value = service.service_type_id;
                        option.text = service.service_type.service_name;
                        serviceTypeSelect.appendChild(option);
                    });
                    enableSelect('service_type');
                })
                .catch(error => console.error('Error:', error));
        }

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
            fetch(`/api/services/${venueId}/${serviceTypeId}`)
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
            fetch(`/api/packages/${serviceEventId}`)
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
            fetch(`/api/package-details/${packageId}`)
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
                fetch(`/api/print-photo-details/${packageId}`)
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
            serviceTypeSelect.disabled = true;
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
                console.log("selectedDate:", selectedDate);
                console.log("selectedDateString:", selectedDateString);
                console.log("selectedDayIndex:", selectedDayIndex);
                console.log("selectedDayId:", selectedDayId);
                console.log("filteredOpeningHours:", filteredOpeningHours);
                console.log("bookDates:", bookedDates);

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
                console.log("Current Time (minus 30 minutes):", now);
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
