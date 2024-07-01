@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <section class="breadcrumb-section set-bg" data-setbg="/front/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Detail Payment</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('customer.detail-venue', $venueId) }}">Detail Venue</a>
                            <span>Detail Payment</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-primary shadow">
                            <div class="card-header bg-info">
                                <h4 class="h4 text-white">Detail Booking</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 170px;">Kode Pembayaran</th>
                                        <td>{{ $rent->faktur }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Booking</th>
                                        <td>
                                            @if ($rent->rent_status == 4)
                                                Expired
                                            @endif
                                            @if ($rent->rent_status == 5)
                                                Belum Bayar
                                            @endif
                                            @if ($rent->rent_status == 1)
                                                Dibooking
                                            @endif
                                            @if ($rent->rent_status == 1)
                                                Diajukan
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nama Penyewa</th>
                                        <td>{{ $rent->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Booking</th>
                                        <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jadwal</th>
                                        <td>{{ $rent->formatted_schedule }}</td>
                                    </tr>
                                    <tr>
                                        <th>Venue</th>
                                        <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tipe Layanan</th>
                                        <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->serviceType->service_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nama Paket</th>
                                        <td>
                                            {{ $rent->servicePackageDetail->servicePackage->name }} (
                                            {{ $rent->servicePackageDetail->sum_person }} Orang )
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Add On</th>
                                        <td>
                                            @if ($rent->servicePackageDetail->servicePackage->addOnPackageDetails->isNotEmpty())
                                                @foreach ($rent->servicePackageDetail->servicePackage->addOnPackageDetails as $addOnPackageDetail)
                                                    <div class="badge badge-info mb-1"><i
                                                            class="icon-copy dw dw-photo-camera1"></i>
                                                        {{ $addOnPackageDetail->sum }}
                                                        {{ $addOnPackageDetail->addOnPackage->name }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Cetak Foto</th>
                                        <td>
                                            @if ($rent->servicePackageDetail->servicePackage->printPhotoDetails->isNotEmpty())
                                                @foreach ($rent->servicePackageDetail->servicePackage->printPhotoDetails as $printPhotoDetail)
                                                    <div class="badge badge-info mb-1"><i class="icon-copy dw dw-print"></i>
                                                        Size
                                                        {{ $printPhotoDetail->printPhoto->size }}</div>
                                                @endforeach
                                            @else
                                                <div class="badge badge-warning">Tidak ada Cetak Foto</div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Frame Foto</th>
                                        <td>
                                            @if ($rent->servicePackageDetail->servicePackage->printPhotoDetails->isNotEmpty())
                                                @foreach ($rent->servicePackageDetail->servicePackage->framePhotoDetails as $framePhotoDetail)
                                                    <div class="badge badge-info mb-1"><i
                                                            class="icon-copy dw dw-image1"></i>
                                                        Size
                                                        {{ $framePhotoDetail->printPhoto->size }}</div>
                                                @endforeach
                                            @else
                                                <div class="badge badge-warning">Tidak ada Frame Foto</div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Metode Booking</th>
                                        <td>
                                            @if ($rent->book_type == 0)
                                                <div class="badge badge-success"><i class="fa fa-user"></i> Offline
                                                </div>
                                            @elseif ($rent->book_type == 1)
                                                <div class="badge badge-success"><i class="fa fa-user"></i> Online</div>
                                            @else
                                                <div class="badge badge-danger"><i class="fa fa-user"></i> Tidak Valid
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lama Pemotretan</th>
                                        <td>
                                            @if ($rent->servicePackageDetail->time_status == 0)
                                                <div class="badge badge-success"><i class="fa fa-clock"></i> 30 Menit
                                                </div>
                                            @elseif($rent->servicePackageDetail->time_status == 1)
                                                <div class="badge badge-primary"><i class="fa fa-clock"></i> 60 Menit
                                                </div>
                                            @elseif($rent->servicePackageDetail->time_status == 2)
                                                <div class="badge badge-info"><i class="fa fa-clock"></i> 90 Menit</div>
                                            @elseif($rent->servicePackageDetail->time_status == 3)
                                                <div class="badge badge-warning"><i class="fa fa-clock"></i> 120 Menit
                                                </div>
                                            @else
                                                <div class="badge badge-danger"><i class="fa fa-clock"></i> Tidak Valid
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($rent->print_photo_detail_id != null)
                                        <tr>
                                            <th>Cetak Foto</th>
                                            <td>Ukuran
                                                {{ $rent->printPhotoDetail->printServiceEvent->printPhoto->size }} (Rp
                                                {{ number_format($rent->printPhotoDetail->printServiceEvent->price, 0, ',', '.') }})
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Harga Paket</th>
                                        <td>
                                            <div class="badge badge-success"><i class="icon-copy dw dw-money-1"></i> Rp
                                                {{ number_format($rent->total_price, 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-primary shadow">
                            <div class="card-header">
                                <h4 class="h4">Detail Pembayaran</h4>
                                <h5 id="remaining" style="color:red;text-align:center">
                                    Waktu yang tersisa untuk melakukan pembayaran
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('customer.booking.payment-cust', $rent->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <x-alert.form-alert />
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="dp_price"><strong>Jenis Pembayaran</strong></label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="full_option" name="dp_price"
                                                            class="custom-control-input" value="full_payment" checked>
                                                        <label class="custom-control-label" for="full_option">Lunas</label>
                                                    </div>
                                                    @if ($rent->servicePackageDetail->servicePackage->dp_status == 1)
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="dp_option" name="dp_price"
                                                                class="custom-control-input" value="dp">
                                                            <label class="custom-control-label" for="dp_option">DP</label>
                                                            <div id="dp_input_group"
                                                                style="display: none; display: flex; align-items: center; margin-top: 10px;">
                                                                <div class="input-group" style="align-items: center;">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"
                                                                            style="height: 38px;">Rp</span>
                                                                    </div>
                                                                    <input type="number" id="dp_input" name="dp_input"
                                                                        class="form-control"
                                                                        placeholder="Minimal Rp {{ number_format($rent->servicePackageDetail->servicePackage->dp_percentage * $rent->total_price, 0, ',', '.') }}"
                                                                        style="height: 38px; margin-right: 10px;">
                                                                    <div class="badge badge-success"
                                                                        style="height: 38px; display: flex; align-items: center;">
                                                                        <i class="fas fa-money mr-1"></i> DP Minimal Rp
                                                                        {{ number_format($rent->servicePackageDetail->servicePackage->dp_percentage * $rent->total_price, 0, ',', '.') }}
                                                                    </div>
                                                                </div>
                                                                @error('dp_input')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @elseif ($rent->servicePackageDetail->servicePackage->dp_status == 2)
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="min_payment_option" name="dp_price"
                                                                class="custom-control-input" value="min_payment">
                                                            <label class="custom-control-label"
                                                                for="min_payment_option">Minimal
                                                                Pembayaran</label>
                                                            <div id="min_payment_input_group"
                                                                style="display: none; display: flex; align-items: center; margin-top: 10px;">
                                                                <div class="input-group" style="align-items: center;">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"
                                                                            style="height: 38px;">Rp</span>
                                                                    </div>
                                                                    <input type="number" id="min_payment_input"
                                                                        name="min_payment_input" class="form-control"
                                                                        placeholder="Minimal Rp {{ number_format($rent->servicePackageDetail->servicePackage->dp_min, 0, ',', '.') }}"
                                                                        style="height: 38px; margin-right: 10px;">
                                                                    <div class="badge badge-success"
                                                                        style="height: 38px; display: flex; align-items: center;">
                                                                        <i class="fas fa-money mr-1"></i> Min. Bayar Rp
                                                                        {{ number_format($rent->servicePackageDetail->servicePackage->dp_min, 0, ',', '.') }}
                                                                    </div>
                                                                </div>
                                                                @error('min_payment_input')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @error('dp_price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label><strong>Metode Pembayaran</strong></label>
                                                    @foreach ($rent->servicePackageDetail->servicePackage->serviceEvent->venue->paymentMethodDetails as $paymentMethodDetail)
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio"
                                                                id="bank-{{ $paymentMethodDetail->id }}"
                                                                name="paymentMethod" class="custom-control-input"
                                                                value="{{ $paymentMethodDetail->id }}" required>
                                                            <label class="custom-control-label"
                                                                for="bank-{{ $paymentMethodDetail->id }}"><img
                                                                    src="{{ asset('images/icon_bank/' . $paymentMethodDetail->paymentMethod->icon) }}"
                                                                    alt="{{ $paymentMethodDetail->paymentMethod->name }}"
                                                                    width="30"
                                                                    height="30">{{ $paymentMethodDetail->paymentMethod->name }}
                                                                <span style="color: #007bff;">(<span
                                                                        onclick="copyToClipboard('{{ $paymentMethodDetail->no_rek }}', '{{ $paymentMethodDetail->paymentMethod->name }}')"
                                                                        data-toggle="tooltip"
                                                                        title="Klik untuk menyalin nomor {{ $paymentMethodDetail->paymentMethod->name }}"
                                                                        style="cursor: pointer; text-decoration: underline; color: #007bff;">{{ $paymentMethodDetail->no_rek }}</span>)</label>
                                                        </div>
                                                    @endforeach
                                                    @error('paymentMethod')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label><strong>Bukti Pembayaran</strong></label>
                                                    <div class="text-center mb-3">
                                                        <img id="preview_image" src="#" alt="Preview Image"
                                                            style="max-width: 200px; display: none;">
                                                    </div>
                                                    <input type="file" class="form-control" name="bukti_pembayaran"
                                                        id="bukti_pembayaran" accept=".jpg, .jpeg, .png" required>
                                                    <small>Format: .jpg, .jpeg, .png | Max. 2 MB</small>
                                                    @error('bukti_pembayaran')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <input type="hidden" name="payment_type" id="payment_type" value="">
                                            <div class="col-lg-12 mt-2">
                                                <div class="clearfix">
                                                    <div class="pull-right">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">Bayar</button>
                                                        </div>
                                                    </div>
                                                    <div class="pull-left">
                                                        <a href="javascript:history.back()"
                                                            class="btn btn-outline-info">Kembali</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    {{-- preview butki --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var input = document.getElementById('bukti_pembayaran');
            var preview = document.getElementById('preview_image');

            input.addEventListener('change', function() {
                var file = input.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'inline';
                };

                if (file) {
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    {{-- jenis pembayaran  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dpOption = document.getElementById('dp_option');
            const minPaymentOption = document.getElementById('min_payment_option');
            const dpInputGroup = document.getElementById('dp_input_group');
            const minPaymentInputGroup = document.getElementById('min_payment_input_group');
            const fullOption = document.getElementById('full_option');

            function togglePaymentInputs() {
                if (dpOption && dpOption.checked) {
                    dpInputGroup.style.display = 'flex';
                    if (minPaymentInputGroup) minPaymentInputGroup.style.display = 'none';
                } else if (minPaymentOption && minPaymentOption.checked) {
                    if (dpInputGroup) dpInputGroup.style.display = 'none';
                    minPaymentInputGroup.style.display = 'flex';
                } else {
                    if (dpInputGroup) dpInputGroup.style.display = 'none';
                    if (minPaymentInputGroup) minPaymentInputGroup.style.display = 'none';
                }
            }

            if (dpOption) {
                dpOption.addEventListener('change', togglePaymentInputs);
            }
            if (minPaymentOption) {
                minPaymentOption.addEventListener('change', togglePaymentInputs);
            }
            if (fullOption) {
                fullOption.addEventListener('change', function() {
                    if (dpInputGroup) dpInputGroup.style.display = 'none';
                    if (minPaymentInputGroup) minPaymentInputGroup.style.display = 'none';
                });
            }

            togglePaymentInputs();
        });
    </script>
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
    {{-- cek expired --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var rentCreatedAt = new Date("{{ $rent->created_at }}");
            rentCreatedAt.setMinutes(rentCreatedAt.getMinutes() + 30);
            var countDownDate = rentCreatedAt.getTime();

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("remaining").innerHTML =
                    "Waktu yang tersisa untuk melakukan pembayaran " +
                    hours + "h " +
                    minutes + "m " + seconds + "s ";

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("remaining").innerHTML = "JADWAL EXPIRED";
                    var submitButton = document.getElementById("submitPayment");
                    if (submitButton) {
                        submitButton.parentNode.removeChild(submitButton);
                    }
                    $.ajax({
                        url: '{{ route('customer.booking.update-status-rent-cust', $rent->id) }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: 4
                        },
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Waktu Pembayaran Jadwal Booking Sudah Habis',
                        text: 'Silahkan lakukan booking jadwal ulang.',
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ route('customer.detail-venue', $rent->servicePackageDetail->servicePackage->serviceEvent->venue->id) }}";
                        }
                    });
                }
            }, 1000);

            document.querySelector('form').addEventListener('submit', function(event) {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                if (distance < 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Waktu Pembayaran Jadwal Booking Sudah Habis',
                        text: 'Silahkan lakukan booking jadwal ulang.',
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ route('customer.detail-venue', $rent->servicePackageDetail->servicePackage->serviceEvent->venue->id) }}";
                        }
                    });
                }
            });
        });
    </script>
@endpush
