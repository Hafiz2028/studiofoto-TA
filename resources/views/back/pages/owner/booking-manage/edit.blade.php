@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Booking')
@section('content')
    <div class="page-header">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-dark">Edit Booking</h4>
            </div>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.booking.index') }}">Booking List</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Edit Booking
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-primary">Edit Package</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('owner.booking.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="ion-arrow-left-a"></i> Kembali
                        </a>
                    </div>
                </div>
                <hr>
                <form action="{{ route('owner.booking.update', $rent->id) }}" method="POST" enctype="multipart/form-data"
                    class="mt-3" id="editBookingForm{{ $rent->id }}">
                    @csrf
                    @method('PUT')
                    <x-alert.form-alert />
                    <input type="hidden" id="selected_rent" value="{{ $rent->id }}">
                    <input type="hidden" id="edit-selected-venue-id"
                        value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue_id }}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>1. Nama Penyewa</label>
                                <input type="text" class="form-control" name="name_tenant" value="{{ $rent->name }}"
                                    placeholder="Booking jadwal atas nama..." disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="edit-venue_id">2. Nama Venue</label>
                                <input type="text" class="form-control venue-id" id="edit-venue_name" name="venue_name"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }} {{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue_id }}"
                                    disabled>
                                <input type="hidden" id="edit-selected-venue-id"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue_id }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>3. Tipe Layanan</label>
                                <input class="form-control service-type" id="service_type" name="service_type"
                                    type="text"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->serviceType->service_name }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>4. Nama Layanan</label>
                                <input class="form-control service-event" id="service_event" name="service" type="text"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->name }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>5. Nama Paket</label>
                                @php
                                    $servicePackageName = $rent->servicePackageDetail->servicePackage->name;
                                    $addOnDetails = '';
                                    if (
                                        $rent->servicePackageDetail->servicePackage->addOnPackageDetails->isNotEmpty()
                                    ) {
                                        $addOnDetailsArray = [];
                                        foreach (
                                            $rent->servicePackageDetail->servicePackage->addOnPackageDetails
                                            as $addOnPackageDetail
                                        ) {
                                            $addOnDetailsArray[] =
                                                '+ (' .
                                                $addOnPackageDetail->sum .
                                                ' ' .
                                                $addOnPackageDetail->addOnPackage->name .
                                                ')';
                                        }
                                        $addOnDetails = implode(' ', $addOnDetailsArray);
                                    }
                                    $inputValue = trim($servicePackageName . ' ' . $addOnDetails);
                                @endphp
                                <input class="form-control package" id="package" name="package" type="text"
                                    value="{{ $inputValue }}" disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>6. Jumlah Orang & Harga</label>
                                <input class="form-control package-detail" id="edit-package-detail" name="package_detail"
                                    data-edit-package-id="{{ $rent->service_package_detail_id }}"
                                    data-time-status="{{ $rent->servicePackageDetail->time_status }}" type="text"
                                    value="{{ $rent->servicePackageDetail->sum_person }} Orang - Rp {{ number_format($rent->servicePackageDetail->price, 0, 0) }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="d-flex flex-column justify-content-center align-items-center mt-3"
                                id="badge-container">
                                @if ($rent->servicePackageDetail->time_status == 0)
                                    <div class="badge badge-success mb-1 time-badge" data-time-status="0">30 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 1)
                                    <div class="badge badge-primary mb-1 time-badge" data-time-status="1">60 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 2)
                                    <div class="badge badge-info mb-1 time-badge" data-time-status="2">90 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 3)
                                    <div class="badge badge-warning mb-1 time-badge" data-time-status="3">120 Menit
                                    </div>
                                @else
                                    <div class="badge badge-danger mb-1 time-badge" data-time-status="-1">Tidak Valid
                                    </div>
                                @endif

                                @if ($rent->servicePackageDetail->servicePackage->dp_status == 0)
                                    <div class="badge badge-info dp-badge">Hanya Lunas</div>
                                @elseif ($rent->servicePackageDetail->servicePackage->dp_status == 1)
                                    <div class="badge badge-success dp-badge">DP Minimal
                                        {{ $rent->servicePackageDetail->servicePackage->dp_percentage * 100 }}%</div>
                                @elseif ($rent->servicePackageDetail->servicePackage->dp_status == 2)
                                    <div class="badge badge-success dp-badge">Min. Bayar Rp
                                        {{ number_format($rent->servicePackageDetail->servicePackage->dp_min) }}</div>
                                @else
                                    <div class="badge badge-danger dp-badge">Tidak Valid</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <label>7. Print Foto & Harga</label>
                            <input type="hidden" id="hidden_print_photo_detail_id" name="print_photo_detail_id"
                                value="{{ $rent->print_photo_detail_id }}">
                            <input class="form-control print-photo-detail" id="print_photo_detail"
                                name="print_photo_detail"
                                value="{{ is_null($rent->print_photo_detail_id) ? 'Tidak ada Cetak Foto' : 'Size ' . $rent->printPhotoDetail->printServiceEvent->printPhoto->size . ' - Harga Rp ' . number_format($rent->printPhotoDetail->printServiceEvent->price) }}"
                                disabled>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>8. Tanggal Booking</label>
                                <input type="text" class="form-control date" id="edit-date" name="date"
                                    value="{{ \Carbon\Carbon::parse($rent->date)->format('d/m/Y') }}"
                                    pattern="\d{2}/\d{2}/\d{4}" required>
                                <small class="text-muted">Format: DD/MM/YYYY</small>
                                <input type="hidden" id="edit-opening-hours" value="{{ json_encode($openingHours) }}">
                                <input type="hidden" id="edit-unique-days" value="{{ json_encode($uniqueDayIds) }}">
                                <input type="hidden" id="book-dates" value="{{ json_encode($bookDates) }}">
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-12">
                            <div class="form-group">
                                <label>9. Jadwal Tersedia</label>
                                <div class="card">
                                    <div class="card-header d-flex justify-content-end">
                                        <div class="badge badge-primary">Tersedia</div>
                                        <div class="badge badge-secondary ml-1">Tutup</div>
                                        <div class="badge badge-danger ml-1">Telah dibooking</div>
                                        <div class="badge badge-success ml-1">Dipilih</div>
                                    </div>
                                    <div id="edit-schedule-container" class="card-body">
                                        <div class="alert alert-info">Belum memilih tanggal dan venue</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="opening_hours[]" id="opening-hours-input">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="form-group d-flex align-items-center">
                                <label for="package-price">Harga Paket Foto</label>
                                <span id="package-price" class="badge badge-primary ml-2">Rp
                                    {{ number_format($rent->servicePackageDetail->price) }}</span>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <label for="print-photo-price">Harga Cetak Foto</label>
                                <span id="print-photo-price" class="badge badge-info ml-2">Rp
                                    {{ $rent->printPhotoDetail ? number_format($rent->printPhotoDetail->printServiceEvent->price) : number_format(0) }}
                                </span>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <label for="total-price">Harga Total</label>
                                <span id="total-price" class="badge badge-success ml-2">Rp
                                    {{ number_format($rent->total_price) }}</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="total_price" id="total-price-input" value="{{ $rent->total_price }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dayMapping = {
                0: 7,
                1: 1,
                2: 2,
                3: 3,
                4: 4,
                5: 5,
                6: 6
            };
            var editDateInput = document.getElementById('edit-date');
            var editSelectedVenueIdInput = document.getElementById('edit-selected-venue-id');
            var editScheduleContainer = document.getElementById('edit-schedule-container');
            var editOpeningHoursData = JSON.parse(document.getElementById('edit-opening-hours').value);
            var editUniqueDaysInput = document.getElementById('edit-unique-days');
            var bookDates = JSON.parse(document.getElementById('book-dates').value);
            console.log('Data bookDates:', bookDates);
            var editSelectedRentId = document.getElementById('selected_rent').value;
            var timeStatus = parseInt(document.querySelector('[data-time-status]').getAttribute(
                'data-time-status'));
            var packageDetailId = document.querySelector('[data-edit-package-id]').getAttribute(
                'data-edit-package-id');
            initializeDatePicker(editDateInput, editScheduleContainer, editSelectedRentId,
                timeStatus, packageDetailId);
            initializeScheduleForEdit(editDateInput, editSelectedVenueIdInput,
                editScheduleContainer, editSelectedRentId, timeStatus, packageDetailId);

            function updateOpeningHoursForEdit(openingHoursData, bookDates, selectedDateString, editSelectedVenueId,
                editSelectedRentId, editScheduleContainer, timeStatus) {
                var dateParts = selectedDateString.split('-');
                if (dateParts.length !== 3) {
                    console.error('Invalid date format:', selectedDateString);
                    return;
                }

                var selectedDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                if (isNaN(selectedDate.getTime())) {
                    console.error('Invalid date:', selectedDateString);
                    return;
                }

                var selectedDayIndex = selectedDate.getDay();
                var selectedDayId = dayMapping[selectedDayIndex];

                var filteredOpeningHours = openingHoursData.filter(function(openingHour) {
                    return openingHour.venue_id == editSelectedVenueId && openingHour.day_id ==
                        selectedDayId;
                });

                var filteredRentDetails = bookDates.filter(function(rentDetail) {
                    return rentDetail.rent_id == editSelectedRentId && rentDetail.date ==
                        selectedDateString;
                });

                editScheduleContainer.innerHTML = '';
                if (filteredOpeningHours.length === 0) {
                    var noScheduleAlert = document.createElement('div');
                    noScheduleAlert.classList.add('alert', 'alert-danger');
                    noScheduleAlert.textContent = 'Tidak ada jadwal venue';
                    editScheduleContainer.appendChild(noScheduleAlert);
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
                    var isSelected = filteredRentDetails.some(function(rentDetail) {
                        return rentDetail.opening_hour_id == openingHour.id;
                    });
                    var isBooked = bookDates.some(function(bookedDate) {
                        return bookedDate.opening_hour_id == openingHour.id && bookedDate.date ==
                            selectedDateString && bookedDate.rent_id !== editSelectedRentId && !
                            isSelected;
                    });

                    var badgeClass = 'btn-primary';
                    if (isSelected) {
                        badgeClass = 'btn-success';
                    } else if (isBooked) {
                        badgeClass = 'btn-danger';
                    } else if (openingHour.status == 1) {
                        badgeClass = 'btn-secondary';
                    } else if (selectedDate.toDateString() === now.toDateString() && now > scheduleTime) {
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

                    if (openingHour.status == 1 || (isBooked && !isSelected) || (selectedDate
                        .toDateString() === now.toDateString() && now > scheduleTime)) {
                        labelElement.classList.add('btn-secondary-disabled');
                        labelElement.style.pointerEvents = 'none';
                        labelElement.style.opacity = '0.65';
                    } else {
                        labelElement.addEventListener('click', function() {
                            editHandleScheduleSelection(labelElement, inputElement, openingHour.id,
                                timeStatus, editSelectedRentId);
                        });
                    }
                    if (isSelected) {
                        inputElement.checked = true;
                    }
                    editScheduleContainer.appendChild(inputElement);
                    editScheduleContainer.appendChild(labelElement);
                });
            }

            function editHandleScheduleSelection(clickedLabel, clickedInput, clickedId, timeStatus,
                editSelectedRentId) {
                var allLabels = Array.from(document.querySelectorAll('label.schedule-btn'));
                var allInputs = Array.from(document.querySelectorAll('input[name="opening_hours[]"]'));
                var slotsToSelect = timeStatus + 1;
                var startIndex = allLabels.indexOf(clickedLabel);
                var endIndex = startIndex + slotsToSelect;

                allLabels.forEach(function(label) {
                    label.classList.remove('btn-success');
                    label.classList.add('btn-primary');
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
                    if (allLabels[i].classList.contains('btn-secondary-disabled') || allLabels[i].classList
                        .contains('btn-danger')) {
                        validSelection = false;
                        break;
                    }
                }

                if (validSelection) {
                    for (var i = startIndex; i < endIndex; i++) {
                        allLabels[i].classList.add('btn-success');
                        allLabels[i].classList.remove('btn-primary');
                        allInputs[i].checked = true;
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Jadwal tidak bisa dipilih',
                        text: 'Jadwal yang dipilih tidak bisa dipilih karena ada yang tutup/telah dibooking'
                    });
                }
                console.log('Total opening_hours yang dipilih:', Array.from(allInputs).filter(input => input
                    .checked).length);
            }

            function disableUnavailableDates(date, uniqueDays) {
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                var selectedDayId = dayMapping[date.getDay()];
                if (date.getTime() >= today.getTime() && uniqueDays.includes(selectedDayId)) {
                    return [true, "", ""];
                }
                return [false];
            }

            function initializeDatePicker(editDateInput, editScheduleContainer, editSelectedRentId, timeStatus,
                packageDetailId) {
                var editOpeningHoursData = JSON.parse(document.getElementById('edit-opening-hours').value);
                var editUniqueDaysInput = document.getElementById('edit-unique-days');
                var bookDates = JSON.parse(document.getElementById('book-dates').value);

                $(editDateInput).datepicker({
                    dateFormat: 'dd/mm/yy',
                    beforeShowDay: function(date) {
                        return disableUnavailableDates(date, JSON.parse(editUniqueDaysInput.value));
                    },
                    onSelect: function(dateText) {
                        var selectedDate = new Date(dateText.split('/').reverse().join('-'));
                        var selectedDateString = selectedDate.toISOString().split('T')[0];
                        updateOpeningHoursForEdit(editOpeningHoursData, bookDates, selectedDateString,
                            editSelectedVenueIdInput.value, editSelectedRentId,
                            editScheduleContainer, timeStatus);
                    }
                });
                $(editDateInput).datepicker('setDate', new Date($(editDateInput).val().split('/').reverse().join(
                    '-')));
            }

            function initializeScheduleForEdit(editDateInput, editSelectedVenueIdInput, editScheduleContainer,
                editSelectedRentId, timeStatus, packageDetailId) {
                var editOpeningHoursData = JSON.parse(document.getElementById('edit-opening-hours').value);
                var bookDates = JSON.parse(document.getElementById('book-dates').value);
                var selectedDate = new Date(editDateInput.value.split('/').reverse().join('-'));
                var selectedDateString = selectedDate.toISOString().split('T')[0];
                updateOpeningHoursForEdit(editOpeningHoursData, bookDates, selectedDateString,
                    editSelectedVenueIdInput.value, editSelectedRentId, editScheduleContainer, timeStatus);
            }
            var form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                var openingHoursInput = document.getElementById('opening-hours-input');
                var uniqueOpeningHours = [...new Set(openingHoursInput.value.split(','))];
                openingHoursInput.value = uniqueOpeningHours.join(',');
            });
        });
    </script>
@endpush
