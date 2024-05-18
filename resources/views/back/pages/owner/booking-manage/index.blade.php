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
                            <h4 class="h4 text">List Transaksi Booking Studio Foto</h4>
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
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Service Type</th>
                                    <th>Package</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="sortable_services">
                                <td>03-03-2024</td>
                                <td>Studio Foto Unand</td>
                                <td>Wisuda</td>
                                <td>Paket 1 + Cetak Foto</td>
                                <td>09.30-10.00</td>
                                <td>
                                    <span class="badge badge-info ">Booking Diajukan</span>
                                    <span class="badge badge-danger ">Booking Ditolak</span>
                                    <span class="badge badge-primary ">Selesai</span>
                                    <span class="badge badge-success ">Berhasil Booking</span>
                                    <span class="badge badge-warning ">Kadaluarsa</span>
                                </td>
                                <td>
                                    <a href="" class="btn btn-info">
                                        <i class="bi bi-info-lg"></i> Detail
                                    </a>
                                    <a href="" class="btn btn-primary">
                                        <i class="dw dw-edit2"></i> Edit
                                    </a>
                                    <a type="button" class="btn btn-danger text-white" data-toggle="modal" data-target="">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                    <a type="button" class="btn btn-success text-white" data-toggle="modal" data-target="">
                                        <i class="bi bi-check-lg"></i> Terima
                                    </a>
                                    <a type="button" class="btn btn-danger text-white" data-toggle="modal" data-target="">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </a>
                                </td>
                                {{-- @forelse ($customer as $item )
                                <tr data-index="{{ $item->id}}" data-ordering="">
                                    <td>{{ $item->name}}</td>
                                    <td>{{ $item->email}}</td>
                                    <td>{{ $item->handphone}}</td>
                                    <td>
                                        <div class="table-actions">
                                                <a href="{{ route('admin.user.customer.edit',$item->id)}}" class="text-primary">
                                                <i class="dw dw-edit2"></i>
                                            </a>
                                            <a type="button" class="text-danger" data-toggle="modal" data-target="#exampleModal{{$item->id}}">
                                                <i class="dw dw-delete-3"></i>
                                              </a>
                                            <form method="POST" action="{{ route('admin.user.customer.destroy',$item->id)}}" id="deleteForm" >
                                                @csrf
                                                @method('DELETE')
                                                  <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body">
                                                          Are you sure to delete this user?
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                            </div>
                                            </form>

                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-danger">No services found!</span>
                                        </td>
                                    </tr>
                                @endforelse --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        var selectedPackageDetailId;
        var packageDetails = {!! json_encode($packageDetails) !!};

        function populateServiceTypes() {
            var venueId = document.getElementById('venue_id').value;
            var serviceTypeSelect = document.getElementById('service_type');
            var serviceEventSelect = document.getElementById('service_event');
            var packageSelect = document.getElementById('package');
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');

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

            serviceEventSelect.innerHTML = '<option value="" disabled selected>Pilih Layanan...</option>';
            serviceEventSelect.setAttribute('disabled', true);
            packageSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
            packageSelect.setAttribute('disabled', true);
            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            printPhotoDetailSelect.setAttribute('disabled', true);

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

            packageSelect.innerHTML = '<option value="" disabled selected>Pilih Paket Foto...</option>';
            packageSelect.setAttribute('disabled', true);
            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            printPhotoDetailSelect.setAttribute('disabled', true);

            var hasPackages = false;
            @foreach ($packages as $package)
                if ({{ $package->service_event_id }} == serviceEventId) {
                    var option = document.createElement('option');
                    option.value = "{{ $package->id }}";
                    option.text = "{{ $package->name }}";
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

            packageDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Jumlah Orang...</option>';
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';
            printPhotoDetailSelect.setAttribute('disabled', true);

            var hasPackageDetails = false;
            @foreach ($packageDetails as $packageDetail)
                if ({{ $packageDetail->service_package_id }} == packageId) {
                    var option = document.createElement('option');
                    option.value = "{{ $packageDetail->id }}";
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
            printPhotoDetailSelect.innerHTML = '<option value="" disabled selected>Pilih Cetak Foto...</option>';

            var noPrintPhotoOption = document.createElement('option');
            noPrintPhotoOption.value = "no_print_photo";
            noPrintPhotoOption.text = "Tidak Cetak Foto";
            printPhotoDetailSelect.appendChild(noPrintPhotoOption);

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
        }

        function updatePaymentMethodBadge() {
            var badgeContainer = document.getElementById('badge-container');
            if (selectedPackageDetailId) {
                var packageDetail = packageDetails.find(function(detail) {
                    return detail.id == selectedPackageDetailId;
                });
                if (packageDetail) {
                    var dpStatus = packageDetail.dp_status;
                    while (badgeContainer.firstChild) {
                        badgeContainer.removeChild(badgeContainer.firstChild);
                    }
                    var badge = document.createElement('div');
                    badge.classList.add('badge');
                    if (dpStatus == 0) {
                        badge.classList.add('badge-info');
                        badge.textContent = 'Hanya Lunas';
                    } else if (dpStatus == 1) {
                        badge.classList.add('badge-success');
                        badge.textContent = 'DP Minimal ' + (packageDetail.dp_percentage * 100) + '%';
                    } else if (dpStatus == 2) {
                        badge.classList.add('badge-success', 'mt-2');
                        var minimalPayment = Math.round(packageDetail.dp_percentage * packageDetail.price / 1000) * 1000;
                        var minPayment = minimalPayment.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                        badge.textContent = 'Min. Bayar ' + minPayment;
                    } else {
                        badge.classList.add('badge-danger', 'mt-2');
                        badge.textContent = 'Tidak Ada Metode Pembayaran';
                    }
                    badgeContainer.appendChild(badge);
                }
            }
        }

        function isPrintPhotoDetailSelected() {
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            return printPhotoDetailSelect.value !== '';
        }

        function isPackageDetailSelected() {
            var packageDetailSelect = document.getElementById('package_detail');
            return packageDetailSelect.value !== '';
        }


        document.addEventListener('DOMContentLoaded', function() {
            var serviceTypeSelect = document.getElementById('service_type');
            var serviceEventSelect = document.getElementById('service_event');
            var packageSelect = document.getElementById('package');
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var dateInput = document.getElementById('date');
            var uniqueDaysInput = document.getElementById('unique-days');
            var venueIdInput = document.getElementById('venue_id');
            var scheduleContainer = document.getElementById('schedule-container');
            selectedPackageDetailId = null;
            serviceTypeSelect.setAttribute('disabled', true);
            serviceEventSelect.setAttribute('disabled', true);
            packageSelect.setAttribute('disabled', true);
            packageDetailSelect.setAttribute('disabled', true);
            printPhotoDetailSelect.setAttribute('disabled', true);
            var openingHours = JSON.parse(document.getElementById('opening-hours').value);
            if (dateInput) {
                $(dateInput).datepicker({
                    dateFormat: 'yy-mm-dd',
                    beforeShowDay: disableUnavailableDates
                });
            } else {
                console.error('Elemen dengan id "date" tidak ditemukan.');
            }
            function updateOpeningHoursForVenue(venueId) {
                var filteredOpeningHours = openingHours.filter(function(hour) {
                    return hour.venue_id == venueId;
                });
                var uniqueDayIds = [...new Set(filteredOpeningHours.map(item => item.day_id))];
                if (uniqueDaysInput) {
                    uniqueDaysInput.value = JSON.stringify(uniqueDayIds);
                    $(dateInput).datepicker('refresh');
                } else {
                    console.error('Elemen dengan id "unique-days" tidak ditemukan.');
                }
            }
            function disableUnavailableDates(date) {
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                var uniqueDays = JSON.parse(uniqueDaysInput.value);
                if (date.getTime() >= today.getTime() && uniqueDays.includes(date.getDay())) {
                    return [true, "", ""];
                }
                return [false];
            }
            function toggleDateInput() {
                var printPhotoDetailSelect = document.getElementById('print_photo_detail');
                var dateInput = document.getElementById('date');
                var isPrintPhotoSelected = printPhotoDetailSelect.value !== '';
                var isPackageSelected = isPackageDetailSelected();
                var isDisabled = !(isPrintPhotoSelected && isPackageSelected);
                dateInput.disabled = isDisabled;
            }
            document.getElementById('venue_id').addEventListener('change', function() {
                var venueId = this.value;
                updateOpeningHoursForVenue(venueId);
            });
            // true
            function checkOpeningHours(openingHours, date) {
                var dayId = date.getDay();
                var venueId = document.getElementById('venue_id').value;
                return openingHours.some(hour => hour.day_id === dayId && hour.venue_id == venueId);
            }
            toggleDateInput();
            document.getElementById('print_photo_detail').addEventListener('change',
                toggleDateInput);
            document.getElementById('package_detail').addEventListener('change', toggleDateInput);
            var dateInput = document.getElementById('date');
            var uniqueDaysInput = document.getElementById('unique-days');
            if (dateInput) {
                console.log('Element dateInput found');
            } else {
                console.error('Element dateInput not found');
            }
            if (venueIdInput) {
                console.log('Element venueIdInput found');
            } else {
                console.error('Element venueIdInput not found');
            }
            $(dateInput).datepicker({
                dateFormat: 'yy-mm-dd',
                beforeShowDay: disableUnavailableDates
            });
            dateInput.addEventListener('change', function() {
                console.log("Date changed!");
                var dateValue = this.value;
                console.log("Raw date input value:", dateValue);
                if (!/^\d{4}-\d{2}-\d{2}$/.test(dateValue)) {
                    console.error("Invalid Date Format:", dateValue);
                    return;
                }
                var selectedDate = new Date(dateValue);
                if (isNaN(selectedDate.getTime())) {
                    console.error("Invalid Date:", dateValue);
                    return;
                }
                var selectedDay = selectedDate.getDay();
                var selectedVenueId = venueIdInput.value;
                console.log("Selected Date:", selectedDate);
                var filteredOpeningHours = openingHours.filter(function(hour) {
                    return hour.venue_id == selectedVenueId && hour.day_id == selectedDay;
                });
                console.log("Filtered Opening Hours:", filteredOpeningHours);
                scheduleContainer.innerHTML = '';
                filteredOpeningHours.forEach(function(hour) {
                    console.log("Hour:", hour.hour);
                    var badgeClass = hour.status == 2 ? 'badge-primary' : 'badge-secondary';
                    var hourText = hour.hour.hour;
                    var badgeElement = document.createElement('div');
                    badgeElement.classList.add('badge', 'badge-pill', badgeClass);
                    badgeElement.textContent = hourText;
                    scheduleContainer.appendChild(badgeElement);
                });
            });
            venueIdInput.addEventListener('change', function() {
                console.log('Venue selection changed, triggering date change event');
                dateInput.dispatchEvent(new Event('change'));
            });
        });
    </script>
@endpush
