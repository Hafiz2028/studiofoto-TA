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
                    <div class="clearfix mb-3">
                        <x-alert.form-alert />
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
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus">#</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Venue</th>
                                <th>No HP / WA</th>
                                <th>Jadwal Foto</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th class="datatable-nosort">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="sortable_services">
                            @if (!$rents->count() > 0)
                                <tr>
                                    <td colspan="10">
                                        <div class="alert alert-info text-center">Tidak Ada Venue yang Dibooking.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($rents as $rent)
                                    <tr id="rent-{{ $rent->id }}">
                                        <td class="table-plus">{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                                        <td>{{ $rent->name }}
                                        </td>
                                        <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
                                        </td>
                                        <td>
                                            @php
                                                $no_hp = $rent->no_hp;
                                                if (substr($no_hp, 0, 2) == '08') {
                                                    $no_hp = '628' . substr($no_hp, 2);
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $no_hp }}" target="_blank"
                                                data-toggle="tooltip" title="Chat Tenant {{ $rent->name }}"
                                                data-placement="auto" style="text-decoration: underline; color: blue;">
                                                {{ $no_hp }}
                                            </a>
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
                                                    <span class="badge badge-info "><i class="icon-copy dw dw-question"></i>
                                                        Diajukan</span>
                                                @elseif ($rent->rent_status == 1)
                                                    <span class="badge badge-success "><i
                                                            class="icon-copy dw dw-checked"></i> Dibooking</span>
                                                @elseif ($rent->rent_status == 2)
                                                    <span class="badge badge-primary "><i
                                                            class="icon-copy fa fa-calendar-check-o" aria-hidden="true"></i>
                                                        Selesai</span>
                                                @elseif ($rent->rent_status == 3)
                                                    <span class="badge badge-danger "><i class="icon-copy dw dw-cancel"></i>
                                                        Ditolak</span>
                                                @elseif ($rent->rent_status == 4)
                                                    <span class="badge badge-secondary "><i
                                                            class="icon-copy dw dw-calendar-8"></i> Expired</span>
                                                @elseif ($rent->rent_status == 5)
                                                    <span class="badge badge-warning "><i
                                                            class="icon-copy dw dw-money-1"></i> Belum Bayar</span>
                                                @elseif ($rent->rent_status == 6)
                                                    <span class="badge badge-dark "><i class="icon-copy fa fa-camera-retro"
                                                            aria-hidden="true"></i>
                                                        Sedang Foto</span>
                                                @elseif ($rent->rent_status == 7)
                                                    <span class="badge badge-danger "><i
                                                            class="icon-copy fa fa-calendar-times-o" aria-hidden="true"></i>
                                                        Dibatalkan</span>
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
                                                    <span class="badge badge-success"><i
                                                            class="icon-copy dw dw-money-2"></i> Lunas</span>
                                                @elseif ($rent->dp_price < $rent->total_price && $rent->dp_price != null)
                                                    <span class="badge badge-info "><i class="icon-copy dw dw-money-2"></i>
                                                        Dp (Rp
                                                        {{ number_format($rent->dp_price) }})</span>
                                                @elseif ($rent->dp_price == null)
                                                    <span class="badge badge-warning "><i
                                                            class="icon-copy dw dw-question"></i> Belum Bayar</span>
                                                @else
                                                    <span class="badge badge-danger "><i class="icon-copy dw dw-cancel"></i>
                                                        Tidak Valid</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a href="{{ route('owner.booking.show', $rent->id) }}"
                                                        class="dropdown-item text-info" data-toggle="tooltip"
                                                        data-placement="auto" title="Detail Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="dw dw-eye"></i> Detail</a>
                                                    <script>
                                                        const editUrlBase = "{{ route('owner.booking.edit', ':id') }}";
                                                    </script>
                                                    @if ($rent->rent_status >= 0 && $rent->rent_status <= 7)
                                                        @if ($rent->rent_status == 0)
                                                            <a href="" class="dropdown-item text-success"
                                                                data-toggle="modal" data-target=""
                                                                data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                                    class="icon-copy dw dw-checked"></i> Accept</a>
                                                            <a href="" class="dropdown-item text-danger"
                                                                data-toggle="modal" data-target=""
                                                                data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                                    class="icon-copy dw dw-cancel"></i> Reject</a>
                                                        @endif
                                                        @if ($rent->rent_status == 1 || $rent->rent_status == 5)
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item text-primary edit-schedule"
                                                                data-rent-id="{{ $rent->id }}" data-toggle="tooltip"
                                                                data-placement="auto" title="Edit Jadwal">
                                                                <i class="dw dw-edit2"></i> Edit
                                                            </a>
                                                        @endif
                                                        @if ($rent->rent_status == 5)
                                                            <a href="{{ route('owner.booking.show-payment', ['booking' => $rent->id]) }}"
                                                                class="dropdown-item text-secondary" data-toggle="tooltip"
                                                                data-placement="auto" title="Selesaikan Pembayaran"
                                                                data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                                    class="icon-copy dw dw-money-1"></i> Finish Payment</a>
                                                        @endif
                                                        @if ($rent->rent_status == 3 || $rent->rent_status == 4 || $rent->rent_status == 5 || $rent->rent_status == 7)
                                                            <a href="javascript:;"
                                                                class="dropdown-item text-danger deleteBookingBtn"
                                                                data-rent-id="{{ $rent->id }}" data-toggle="tooltip"
                                                                data-rent-name="{{ $rent->name }}"
                                                                data-placement="auto" title="Hapus Booking"
                                                                data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                                    class="icon-copy dw dw-delete-3"></i> Delete</a>
                                                        @endif
                                                    @else
                                                        <div class="alert alert-danger">Tidak Valid</div>
                                                    @endif
                                                </div>
                                            </div>
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
    {{-- Hapus Booking --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.deleteBookingBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const rentId = this.getAttribute('data-rent-id');
                    const rentName = this.getAttribute('data-rent-name');
                    Swal.fire({
                        title: 'Hapus Jadwal',
                        text: `Apakah Anda yakin ingin menghapus Jadwal ${rentName}? Data Jadwal yang dihapus tidak bisa dikembalikan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Batal',
                        cancelButtonColor: '#28a745',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ route('owner.booking.destroy', ['booking' => ':booking']) }}`
                                .replace(':booking', rentId), {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                }).then(response => {
                                if (response.ok) {
                                    return response.json();
                                } else {
                                    throw new Error(
                                        'Gagal menghapus Jadwal Booking');
                                }
                            }).then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Hapus Jadwal Booking',
                                        text: `Jadwal ${rentName} Berhasil dihapus.`,
                                        icon: 'success',
                                        showConfirmButton: true,
                                        timer: 3000,
                                        timerProgressBar: true,
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    throw new Error(data.message ||
                                        'Gagal menghapus Jadwal Booking');
                                }
                            }).catch(error => {
                                console.error('Terjadi kesalahan:', error);
                                Swal.fire({
                                    title: 'Gagal Hapus Jadwal',
                                    text: "Terjadi kesalahan saat menghapus jadwal.",
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>

    {{-- cek jadwal expired --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('refresh-schedule').addEventListener('click', function() {
                checkExpiredSchedule();
            });

            document.querySelectorAll('.edit-schedule').forEach(function(button) {
                button.addEventListener('click', function() {
                    const rentId = this.getAttribute('data-rent-id');
                    checkExpiredSchedule(function(isExpired) {
                        if (!isExpired) {
                            const editUrl = editUrlBase.replace(':id', rentId);
                            setTimeout(function() {
                                window.location.href = editUrl;
                            }, 1000);
                        }
                    });
                });
            });
        });

        function checkExpiredSchedule(callback) {
            $.ajax({
                url: '{{ route('owner.booking.update-status') }}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Jadwal Expired',
                            html: data.message.join('<br>')
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                        if (callback) callback(true);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Jadwal Tidak Expired',
                            text: data.message
                        });
                        if (callback) callback(false);
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                    if (callback) callback(true);
                }
            });
        }
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
        var packages = [];
        var packageDetails = [];

        function populateServicesAndEvents() {
            resetSelectAndDisable('service', 'Pilih Layanan...');
            resetSelectAndDisable('package_detail', 'Pilih Paket Foto...');
            resetSelectAndDisable('date', '');
            document.getElementById('schedule-container').innerHTML =
                '<div class="alert alert-info">Belum memilih tanggal dan venue</div>';
            document.getElementById('date').value = '';
            var serviceEventSelect = document.querySelector('select[name="service"]');
            serviceEventSelect.innerHTML = '<option value="" disabled selected>Pilih Layanan...</option>';
            var venueId = document.getElementById('venue_id').value;
            fetch(`/api/services-and-events/${venueId}`)
                .then(response => response.json())
                .then(serviceTypes => {
                    serviceTypes.forEach(serviceType => {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = serviceType.service_name;

                        serviceType.service_events.forEach(serviceEvent => {
                            const option = document.createElement('option');
                            option.value = serviceEvent.id;
                            option.text = serviceEvent.name;
                            optgroup.appendChild(option);
                        });

                        serviceEventSelect.appendChild(optgroup);
                    });
                    enableSelect('service');
                })
                .catch(error => console.error('Error:', error));
        }

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
            if (!isDisabled) {
                $(dateInput).datepicker('enable');
            } else {
                $(dateInput).datepicker('disable');
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
            serviceEventSelect.disabled = true;
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
                now.setMinutes(now.getMinutes() - 30);
                now.setSeconds(0, 0);
                filteredOpeningHours.forEach(function(openingHour) {
                    var hourText = openingHour.hour.hour;
                    var hourParts = hourText.split('.');
                    var hour = parseInt(hourParts[0], 10);
                    var minute = parseInt(hourParts[1], 10);
                    var scheduleTime = new Date(selectedDate);
                    scheduleTime.setHours(hour, minute, 0, 0);
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
@endpush
