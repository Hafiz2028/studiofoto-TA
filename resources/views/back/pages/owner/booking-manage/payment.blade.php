@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Booking List')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Detail Payment</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.booking.index') }}">Booking</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail Payment
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card card-primary shadow">
                <div class="card-header bg-info">
                    <h4 class="h4 text-white">Detail Booking</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 170px;">Nama Penyewa</th>
                            <td>{{ $rent->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Booking</th>
                            <td>{{ $rent->date }}</td>
                        </tr>
                        <tr>
                            <th>Jadwal</th>
                            <td>{{ $rent->formatted_schedule }}</td>
                        </tr>

                        <tr>
                            <th>Venue</th>
                            <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}</td>
                        </tr>
                        <tr>
                            <th>Tipe Layanan</th>
                            <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->serviceType->service_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Nama Paket</th>
                            <td>
                                {{ $rent->servicePackageDetail->servicePackage->name }}
                                @if ($rent->servicePackageDetail->servicePackage->addOnPackageDetails->isNotEmpty())
                                    @foreach ($rent->servicePackageDetail->servicePackage->addOnPackageDetails as $addOnPackageDetail)
                                        + ({{ $addOnPackageDetail->sum }} {{ $addOnPackageDetail->addOnPackage->name }})
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Cetak Foto</th>
                            <td>
                                @if ($rent->print_photo_detail_id > 0)
                                    Size {{ $rent->printPhotoDetail->printServiceEvent->printPhoto->size }} - Harga Rp
                                    {{ number_format($rent->printPhotoDetail->printServiceEvent->price) }}
                                @else
                                    <div class="badge badge-warning">Tidak Cetak Foto</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Metode Booking</th>
                            <td>
                                @if ($rent->book_type == 0)
                                    <div class="badge badge-info"><i class="fa fa-user"></i> Offline</div>
                                @elseif ($rent->book_type == 1)
                                    <div class="badge badge-info"><i class="fa fa-user"></i> Online</div>
                                @else
                                    <div class="badge badge-danger"><i class="fa fa-user"></i> Tidak Valid</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Lama Pemotretan</th>
                            <td>
                                @if ($rent->servicePackageDetail->time_status == 0)
                                    <div class="badge badge-success"><i class="fa fa-clock"></i> 30 Menit</div>
                                @elseif($rent->servicePackageDetail->time_status == 1)
                                    <div class="badge badge-primary"><i class="fa fa-clock"></i> 60 Menit</div>
                                @elseif($rent->servicePackageDetail->time_status == 2)
                                    <div class="badge badge-info"><i class="fa fa-clock"></i> 90 Menit</div>
                                @elseif($rent->servicePackageDetail->time_status == 3)
                                    <div class="badge badge-warning"><i class="fa fa-clock"></i> 120 Menit</div>
                                @else
                                    <div class="badge badge-danger"><i class="fa fa-clock"></i> Tidak Valid</div>
                                @endif
                            </td>
                        </tr>

                        @if ($rent->print_photo_detail_id != null)
                            <tr>
                                <th>Cetak Foto</th>
                                <td>Ukuran {{ $rent->printPhotoDetail->printServiceEvent->printPhoto->size }} (Rp
                                    {{ number_format($rent->printPhotoDetail->printServiceEvent->price, 0, ',', '.') }})
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Total Harga</th>
                            <td>Rp {{ number_format($rent->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card card-primary shadow">
                <div class="card-header bg-primary">
                    <h4 class="h4 text-white text-center">Detail Pembayaran</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.booking.payment', $rent->id) }}" method="POST"
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
                                                            <span class="input-group-text" style="height: 38px;">Rp</span>
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
                                                <label class="custom-control-label" for="min_payment_option">Minimal
                                                    Pembayaran</label>
                                                <div id="min_payment_input_group"
                                                    style="display: none; display: flex; align-items: center; margin-top: 10px;">
                                                    <div class="input-group" style="align-items: center;">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="height: 38px;">Rp</span>
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
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary float-right">Submit
                                            Pembayaran</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('styles')
@endpush
@push('scripts')
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
                title: 'Nomor ' + paymentMethodName + ' telah disalin:',
                text: text
            });
        }

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
