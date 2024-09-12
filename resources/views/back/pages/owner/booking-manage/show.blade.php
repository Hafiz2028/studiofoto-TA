@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Detail Booking')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Detail Booking</h4>
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
                            Detail Booking
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card-box shadow p-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pull-left">
                        @if ($rent->rent_status == 0)
                            <span class="badge badge-info"><i class="icon-copy dw dw-question"></i>
                                Diajukan</span>
                        @elseif ($rent->rent_status == 1)
                            <span class="badge badge-success"><i class="icon-copy dw dw-checked"></i> Dibooking</span>
                        @elseif ($rent->rent_status == 2)
                            <span class="badge badge-primary "><i class="icon-copy fa fa-calendar-check-o"
                                    aria-hidden="true"></i>
                                Selesai</span>
                        @elseif ($rent->rent_status == 3)
                            <span class="badge badge-danger "><i class="icon-copy dw dw-cancel"></i>
                                Ditolak</span>
                        @elseif ($rent->rent_status == 4)
                            <span class="badge badge-secondary "><i class="icon-copy dw dw-calendar-8"></i> Expired</span>
                        @elseif ($rent->rent_status == 5)
                            <span class="badge badge-warning "><i class="icon-copy dw dw-money-1"></i> Belum Bayar</span>
                        @elseif ($rent->rent_status == 6)
                            <span class="badge badge-dark "><i class="icon-copy fa fa-camera-retro" aria-hidden="true"></i>
                                Sedang Foto</span>
                        @elseif ($rent->rent_status == 7)
                            <span class="badge badge-danger "><i class="icon-copy fa fa-calendar-times-o"
                                    aria-hidden="true"></i>
                                Dibatalkan</span>
                        @else
                            <span class="badge badge-danger ">Tidak Valid</span>
                        @endif
                        @if ($rent->rent_status != 4)
                            @if ($rent->dp_payment != null)
                                <span class="badge badge-success"><i class="icon-copy dw dw-money-2"></i>
                                    Lunas</span>
                            @else
                                @if ($rent->dp_price == $rent->total_price)
                                    <span class="badge badge-success"><i class="icon-copy dw dw-money-2"></i> Lunas</span>
                                @elseif ($rent->dp_price < $rent->total_price && $rent->dp_price != null)
                                    <span class="badge badge-warning "><i class="icon-copy dw dw-money-2"></i>
                                        Dp (Rp
                                        {{ number_format($rent->dp_price) }})</span>
                                @elseif ($rent->dp_price == null)
                                    <span class="badge badge-danger "><i class="icon-copy dw dw-question"></i> Dp (0)</span>
                                @else
                                    <span class="badge badge-danger "><i class="icon-copy dw dw-cancel"></i>
                                        Tidak Valid</span>
                                @endif
                            @endif
                        @else
                            <span class="badge badge-secondary "><i class="icon-copy dw dw-calendar-8"></i> Expired</span>
                        @endif
                    </div>
                    <div class="pull-right">
                        @if ($rent->rent_status == 1)
                            <a href="javascript:;" onclick="printInvoice()" class="btn btn-outline-info"><i
                                    class="icon-copy dw dw-print"></i>
                                Cetak Invoice</a>
                            @include('back.pages.owner.booking-manage.invoice')
                            @if ($rent->dp_payment == null)
                                <a href="{{ route('owner.booking.show-payment-lunas', $rent->id) }}"
                                    class="btn btn-primary"><i class="icon-copy dw dw-money-1"></i>
                                    Pelunasan</a>
                            @endif
                            @if ($rent->dp_payment == null)
                                <a href="javascript:void(0);" class="btn btn-success cekPelunasanBtn"><i
                                        class="icon-copy dw dw-photo-camera-1"></i>
                                    Mulai Foto</a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-success cekJadwalFotoBtn"><i
                                        class="icon-copy dw dw-photo-camera-1"></i>
                                    Mulai Foto</a>
                            @endif
                        @endif
                        @if ($rent->rent_status == 0)
                            <a href="javascript:;" onclick="printInvoice()" class="btn btn-outline-info"><i
                                    class="icon-copy dw dw-print"></i>
                                Cetak Invoice</a>
                            @include('back.pages.owner.booking-manage.invoice')
                        @endif
                        @if ($rent->rent_status == 6)
                            <a href="javascript:;" onclick="printInvoice()" class="btn btn-outline-info"><i
                                    class="icon-copy dw dw-print"></i>
                                Cetak Invoice</a>
                            <a href="javascript:void(0);" class="btn btn-success selesaiFotoBtn"><i
                                    class="icon-copy dw dw-photo-camera-1"></i>
                                Selesai Foto</a>
                        @endif
                        @if ($rent->rent_status == 5)
                            <a href="{{ route('owner.booking.show-payment', ['booking' => $rent->id]) }}"
                                class="btn btn-warning"><i class="icon-copy dw dw-money-1"></i>
                                Pembayaran Awal</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-wrap">
        <div class="product-detail-wrap">
            <div class="row my-3">
                <div
                    class="col-lg-@if ($rent->book_type == 0) 12 @elseif($rent->book_type == 1)6 @endif col-md-12 col-sm-12">
                    <div class="card card-primary shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="text-white mb-0 text-center">Detail Jadwal Booking</h5>
                        </div>
                        <div class="card-body">
                            Berikut Detail Dari Jadwal Booking & Paket
                            yang dipesan :
                            </p>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 180px;">Faktur</th>
                                    <td>{{ $rent->faktur }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Penyewa</th>
                                    <td>{{ ucwords(strtolower($rent->name)) }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Booking</th>
                                    <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Jadwal</th>
                                    <td>
                                        <div class="badge badge-primary"><i class="icon-copy dw dw-wall-clock2"></i>
                                            {{ date('H:i', strtotime(str_replace('.', ':', $firstOpeningHour->hour))) }}
                                        </div> - <div class="badge badge-primary"><i
                                                class="icon-copy dw dw-wall-clock2"></i>
                                            {{ $formattedLastOpeningHour }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Venue</th>
                                    <td>{{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->venue->name)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tipe Layanan</th>
                                    <td>{{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->serviceType->service_name)) }}
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
                                                    {{ $addOnPackageDetail->sum }}
                                                    {{ $addOnPackageDetail->addOnPackage->name }}
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
                                    <th>Include Frame Foto</th>
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
                                            <div class="badge badge-danger"><i class="icon-copy dw dw-money-1"></i> Dp (0)
                                            </div>
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
                                <tr>
                                    <th>Pelunasan</th>
                                    <td>
                                        @if ($rent->dp_payment == null)
                                            <div class="badge badge-warning"><i class="icon-copy dw dw-money-1"></i> Belum
                                                Lunas</div>
                                        @elseif($rent->dp_payment != null)
                                            <div class="badge badge-success"><i class="icon-copy dw dw-money-1"></i> Lunas
                                            </div> ({{ \Carbon\Carbon::parse($rent->dp_payment)->format('H:i:s d M Y') }})
                                        @else
                                            <b>Tidak Valid</b>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($rent->book_type == 1)
                    <div class="col-lg-6 col-md-12 col-sm 12">
                        <div class="card card-primary shadow">
                            <div class="card-header bg-info text-white">
                                <h5 class="text-white mb-0 text-center">Bukti Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                @if ($rent->dp_price != $rent->total_price && $rent->dp_price > 0)
                                    @php
                                        $dpPayment = $rent->rentPayments->firstWhere('payment_type', 'DP');
                                    @endphp
                                    @if ($dpPayment)
                                        <div class="text-center">
                                            <label><strong>Bukti Pembayaran DP</strong></label><br>
                                            <label>Dibayar pada pukul
                                                {{ \Carbon\Carbon::parse($rent->dp_price_date)->format('H:i:s d M Y') }}</label><br>
                                            <img class="img-fluid"
                                                src="{{ asset('images/venues/Bukti_Pembayaran/' . $dpPayment->image) }}"
                                                alt="Bukti Pembayaran DP" style="max-width: 100%; max-height: 500px;">
                                        </div>
                                    @endif
                                    @if ($rent->dp_payment != null)
                                        @php
                                            $fullPayment = $rent->rentPayments->firstWhere('payment_type', 'Lunas');
                                        @endphp
                                        @if ($fullPayment)
                                            <div class="text-center">
                                                <label><strong>Bukti Pembayaran Lunas</strong></label><br>
                                                <label>Dibayar pada pukul
                                                    {{ \Carbon\Carbon::parse($rent->dp_payment)->format('H:i:s d M Y') }}</label><br>
                                                <img class="img-fluid"
                                                    src="{{ asset('images/venues/Bukti_Pembayaran/' . $fullPayment->image) }}"
                                                    alt="Bukti Pembayaran DP" style="max-width: 100%; max-height: 500px;">
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                @if ($rent->dp_price == $rent->total_price && $rent->dp_payment != null)
                                    @php
                                        $fullPayment = $rent->rentPayments->firstWhere('payment_type', 'Lunas');
                                    @endphp
                                    @if ($fullPayment)
                                        <div class="text-center">
                                            <label><strong>Bukti Pembayaran Lunas</strong></label><br>
                                            <label>Dibayar pada pukul
                                                {{ \Carbon\Carbon::parse($rent->dp_payment)->format('H:i:s d M Y') }}</label><br>
                                            <img class="img-fluid"
                                                src="{{ asset('images/venues/Bukti_Pembayaran/' . $fullPayment->image) }}"
                                                alt="Bukti Pembayaran Lunas" style="max-width: 100%; max-height: 500px;">
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('stylesheets')
    <style>
        .print-container {
            display: none;
        }

        @media print {
            .print-container {
                display: block;
            }

            .no-print {
                display: none;
            }
        }

        .invoice-header,
        .invoice-body,
        .invoice-summary {
            margin-bottom: 20px;
        }

        .invoice-header img {
            max-width: 150px;
            height: auto;
        }

        .invoice-header h1,
        .invoice-header h2 {
            margin: 0;
            padding: 0;
        }

        .invoice-body p {
            margin: 5px 0;
        }

        .invoice-table,
        .invoice-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice-table th,
        .invoice-table td,
        .invoice-summary th,
        .invoice-summary td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-table th,
        .invoice-summary th {
            background-color: #f2f2f2;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function printInvoice() {
            var printContents = document.getElementById('print-container').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
    {{-- pelunasan-booking --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cekPelunasanBtn').forEach(button => {
                button.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Pembayaran Booking',
                        text: "Pembayaran Booking belum lunas, apakah ingin dilunasi sekarang?",
                        icon: 'info',
                        showCancelButton: true,
                        cancelButtonText: 'Nanti Setelah Pemotretan',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Lunasi Sekarang'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ route('owner.booking.show-payment-lunas', $rent->id) }}";
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            triggerCekJadwalFoto();
                        }
                    });
                });
            });
        });

        function triggerCekJadwalFoto() {
            const now = moment();
            const bookingDateStr = "{{ $rent->date }}";
            const openingHourStr = "{{ $firstOpeningHour->hour }}".replace('.', ':');
            const bookingDateTimeStr = bookingDateStr + ' ' + openingHourStr;
            const bookingDateTime = moment(bookingDateTimeStr, 'YYYY-MM-DD HH:mm');

            console.log('Current time:', now.format('YYYY-MM-DD HH:mm:ss'));
            console.log('Booking date and time:', bookingDateTime.format('YYYY-MM-DD HH:mm'));

            const diffMinutes = bookingDateTime.diff(now, 'minutes');
            console.log('Difference in minutes:', diffMinutes);

            if (diffMinutes <= 30 && bookingDateTime.isSameOrAfter(now, 'day')) {
                Swal.fire({
                    title: `Jadwal Booking {{ $rent->customer_name }}`,
                    text: `Jadwal Booking {{ $rent->customer_name }} Pada Waktu {{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }} dari Jam ${openingHourStr} - {{ $formattedLastOpeningHour }} akan melakukan pemotretan, apakah anda yakin?`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Jalankan Pemotretan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatusPemotretan();
                    }
                });
            } else {
                Swal.fire({
                    title: `Warning, Jadwal Booking {{ $rent->customer_name }}`,
                    text: `Jadwal {{ $rent->customer_name }} Belum Terhitung 30 menit sebelum jadwal booking (${openingHourStr} - {{ $formattedLastOpeningHour }}, {{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }} ), apakah ingin melakukan pemotretan sekarang?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Jalankan Pemotretan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatusPemotretan();
                    }
                });
            }
        }

        function updateStatusPemotretan() {
            fetch("{{ route('owner.booking.update-status-mulai-foto', ['booking' => $rent->id]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.status === 'expired') {
                        Swal.fire({
                            title: 'Jadwal Ini sudah expired',
                            text: data.message.join('\n'),
                            icon: 'error'
                        }).then(() => {
                            window.location.href = "{{ route('owner.booking.show', $rent->id) }}";
                        });
                    } else if (data.status === 'success') {
                        Swal.fire('Berhasil', 'Selamat Melakukan Pemotretan', 'success').then(() => {
                            window.location.href = "{{ route('owner.booking.show', $rent->id) }}";
                        });
                    } else {
                        Swal.fire('Info', data.message.join('\n'), 'info');
                    }
                });
        }
    </script>

    {{-- mulai-foto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cekJadwalFotoBtn').forEach(button => {
                button.addEventListener('click', function() {
                    console.log('cekJadwalFotoBtn clicked');
                    triggerCekJadwalFoto();
                });
            });
        });
    </script>

    {{-- selesai foto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.selesaiFotoBtn').forEach(button => {
                button.addEventListener('click', function() {
                    confirmSelesaiPemotretan();
                });
            });
        });

        function confirmSelesaiPemotretan() {
            Swal.fire({
                title: 'Selesai Pemotretan',
                text: 'Apakah anda yakin selesaikan pemotretan?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sudah',
                cancelButtonText: 'Belum',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatusSelesai();
                }
            });
        }

        function updateStatusSelesai() {
            const dpPayment = "{{ $rent->dp_payment }}";
            const dpPrice = "{{ $rent->dp_price_date }}"
            console.log('dpPayment:', dpPayment);
            console.log('dpPrice:', dpPrice);
            if (dpPayment === 'null' || dpPayment === '' || dpPayment === null) {
                console.log('dpPayment is null, showing warning alert');
                Swal.fire({
                    title: 'Jadwal Belum lunas',
                    text: 'Jika Ingin Menyelesaikan Jadwal Pemotretan ini, Lakukan pelunasan sekarang',
                    icon: 'warning',
                    confirmButtonText: 'Lunasi Sekarang',
                    confirmButtonColor: '#28a745',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('owner.booking.show-payment-lunas', $rent->id) }}";
                    }
                });
            } else {
                console.log('dpPayment is not null, proceeding with status update');
                fetch("{{ route('owner.booking.update-status-mulai-foto', ['booking' => $rent->id]) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: 2
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil', 'Pemotretan Telah Selesai dilakukan', 'success').then(() => {
                                window.location.href = "{{ route('owner.booking.show', $rent->id) }}";
                            });
                        }
                    });
            }
        }
    </script>
@endpush
