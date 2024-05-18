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

            //total harga
            var totalPriceSpan = document.getElementById('total-price');
            totalPriceSpan.textContent = 0;
            var packagePrice = parseInt(document.getElementById('package_detail').selectedOptions[0].getAttribute(
                'data-price')) || 0;
            var printPhotoPrice = 0;
            var printPhotoText = '';
            if (printPhotoDetailSelect.value !== "no_print_photo") {
                printPhotoPrice = parseInt(printPhotoDetailSelect.selectedOptions[0].getAttribute('data-price')) || 0;
                printPhotoText = ' + (Harga Cetak Foto: Rp' + printPhotoPrice + ')';
            }
            // Hitung total harga
            var totalPrice = packagePrice + printPhotoPrice;

            // Tampilkan total harga
            totalPriceSpan.textContent = totalPrice + printPhotoText;
            //end

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
        document.addEventListener('DOMContentLoaded', function() {
            var serviceTypeSelect = document.getElementById('service_type');
            var serviceEventSelect = document.getElementById('service_event');
            var packageSelect = document.getElementById('package');
            var packageDetailSelect = document.getElementById('package_detail');
            var printPhotoDetailSelect = document.getElementById('print_photo_detail');
            var dateInput = document.getElementById('date');
            var scheduleContainer = document.getElementById('schedule-container');
            var venueIdInput = document.getElementById('venue_id');
            var openingHoursData = JSON.parse(document.getElementById('opening-hours').value);
            var uniqueDaysInput = document.getElementById('unique-days');
            console.log("openingHoursData:", openingHoursData);
            selectedPackageDetailId = null;
            serviceTypeSelect.disabled = true;
            serviceEventSelect.disabled = true;
            packageSelect.disabled = true;
            packageDetailSelect.disabled = true;
            printPhotoDetailSelect.disabled = true;

            function updateOpeningHours(openingHoursData) {
                var selectedDate = new Date(dateInput.value);
                var selectedDayIndex = selectedDate.getDay() + 1; // Sesuaikan untuk penomoran hari
                var dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                var selectedDayName = dayNames[selectedDayIndex - 1];
                var selectedVenueId = venueIdInput.value;

                var filteredOpeningHours = openingHoursData.filter(function(openingHour) {
                    return openingHour.venue_id == selectedVenueId &&
                        openingHour.day && // Periksa apakah `day` tidak null
                        openingHour.day.name &&
                        openingHour.day.name.toLowerCase() == selectedDayName.toLowerCase();
                });

                console.log("selectedDate:", selectedDate);
                console.log("selectedDayIndex:", selectedDayIndex);
                console.log("selectedDayName:", selectedDayName);
                console.log("filteredOpeningHours:", filteredOpeningHours);

                scheduleContainer.innerHTML = '';
                filteredOpeningHours.forEach(function(openingHour) {
                    var hourText = openingHour.hour.hour;
                    var badgeClass = openingHour.status == 2 ? 'badge-primary' : 'badge-secondary';
                    var badgeElement = document.createElement('div');
                    badgeElement.classList.add('badge', 'badge-pill', badgeClass);
                    badgeElement.textContent = hourText;
                    scheduleContainer.appendChild(badgeElement);
                });
            }

            function disableUnavailableDates(date) {
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                var uniqueDays = JSON.parse(uniqueDaysInput.value);
                if (date.getTime() >= today.getTime() && uniqueDays.includes(date.getDay() + 1)) {
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
            }
            venueIdInput.addEventListener('change', function() {
                var venueId = this.value;
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
                    }
                });
            } else {
                console.error('Elemen dengan id "date" tidak ditemukan.');
            }
            dateInput.addEventListener('input', function(event) {
                console.log("Input event triggered");
                console.log("dateInput value:", this.value);

                var selectedDateParts = this.value.split('/');
                if (selectedDateParts.length !== 3) {
                    console.error('Invalid date format:', this.value);
                    return;
                }

                var selectedDate = new Date(
                    selectedDateParts[2],
                    selectedDateParts[1] - 1,
                    selectedDateParts[0]
                );

                if (isNaN(selectedDate.getTime())) {
                    console.error('Invalid date:', this.value);
                    return;
                }

                updateOpeningHours(openingHoursData);
            });

        });
    </script>
@endpush
