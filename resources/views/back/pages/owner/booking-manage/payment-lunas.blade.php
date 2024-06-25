@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Booking List')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Detail Pelunasan</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.booking.index') }}">Booking</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.booking.show', $rent->id) }}">Detail Booking</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail Pelunasan
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
                            <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
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
                                {{ $rent->servicePackageDetail->servicePackage->name }} (
                                {{ $rent->servicePackageDetail->sum_person }} Orang )
                            </td>
                        </tr>
                        <tr>
                            <th>Include Add On</th>
                            <td>
                                @if ($rent->servicePackageDetail->servicePackage->addOnPackageDetails->isNotEmpty())
                                    @foreach ($rent->servicePackageDetail->servicePackage->addOnPackageDetails as $addOnPackageDetail)
                                        <div class="badge badge-info"><i class="icon-copy dw dw-photo-camera1"></i>
                                            {{ $addOnPackageDetail->sum }} {{ $addOnPackageDetail->addOnPackage->name }}
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Include Cetak Foto</th>
                            <td>
                                @if ($rent->servicePackageDetail->servicePackage->printPhotoDetails->isNotEmpty())
                                    @foreach ($rent->servicePackageDetail->servicePackage->printPhotoDetails as $printPhotoDetail)
                                        <div class="badge badge-info"><i class="icon-copy dw dw-print"></i> Size
                                            {{ $printPhotoDetail->printPhoto->size }}</div>
                                    @endforeach
                                @else
                                    <div class="badge badge-warning">Tidak ada Cetak Foto</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Inlcude Frame Foto</th>
                            <td>
                                @if ($rent->servicePackageDetail->servicePackage->printPhotoDetails->isNotEmpty())
                                    @foreach ($rent->servicePackageDetail->servicePackage->framePhotoDetails as $framePhotoDetail)
                                        <div class="badge badge-info"><i class="icon-copy dw dw-image1"></i> Size
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
                                    <div class="badge badge-success"><i class="fa fa-user"></i> Offline</div>
                                @elseif ($rent->book_type == 1)
                                    <div class="badge badge-success"><i class="fa fa-user"></i> Online</div>
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
                        <tr>
                            <th>Harga Paket</th>
                            <td>
                                <div class="badge badge-success"><i class="icon-copy dw dw-money-1"></i> Rp
                                    {{ number_format($rent->total_price, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th>Pembayaran DP</th>
                            <td>
                                @if ($rent->dp_price == 0)
                                    <div class="badge badge-danger"><i class="icon-copy dw dw-money-1"></i> Belum
                                        Bayar</div>
                                @elseif($rent->dp_price < $rent->total_price)
                                    <div class="badge badge-warning"><i class="icon-copy dw dw-money-1"></i> Rp
                                        {{ number_format($rent->dp_price, 0, ',', '.') }}</div>
                                    ({{ \Carbon\Carbon::parse($rent->dp_price_date)->format('H:i:s d M Y') }})
                                @elseif($rent->dp_price == $rent->total_price)
                                    <div class="badge badge-success"><i class="icon-copy dw dw-money-1"></i> Lunas
                                        Rp{{ number_format($rent->dp_price, 0, ',', '.') }}</div>
                                    ({{ \Carbon\Carbon::parse($rent->dp_price_date)->format('H:i:s d M Y') }})
                                @else
                                    <b>Tidak Valid</b>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card card-primary shadow">
                <div class="card-header bg-primary">
                    <h4 class="h4 text-white text-center">Pelunasan Pembayaran</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.booking.payment-lunas', $rent->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <x-alert.form-alert />

                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="ml-1" for="total_price"><strong>Harga Paket</strong></label>
                                        <input type="text" class="form-control"
                                            value="Rp{{ number_format($rent->total_price) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="ml-1" for="dp_price"><strong>Dp Awal</strong></label>
                                        <input type="text" class="form-control"
                                            value="Rp{{ number_format($rent->dp_price) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label><strong>Waktu Dp</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ \Carbon\Carbon::parse($rent->dp_price_date)->format('H:i:s, d M Y') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="ml-1"><strong>Sisa Pembayaran</strong></label>
                                        <h1 class="ml-3" style="color:darkorange;">
                                            Rp{{ number_format($rent->total_price - $rent->dp_price) }}</h1>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-2">
                                    <div class="form-group">
                                        <a class="btn btn-outline-info" href="{{ route('owner.booking.show', $rent->id) }}">Kembali</a>
                                        <button type="submit" class="btn btn-primary float-right">Pelunasan</button>
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
