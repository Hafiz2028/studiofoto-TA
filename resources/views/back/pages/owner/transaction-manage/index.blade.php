@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Transaction Report')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Transaction List</h4>
                </div>
                <nav aria-label="breadcrumb " role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Transaction List
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-box mb-30 pd-20">
                <h4 class="h4 text">Transaction Booking Studio Owner <span
                        class="text-primary">{{ ucfirst(Auth::user()->name) }}</span></h4>
                <form action="{{ route('owner.transaction.index') }}" method="GET">
                    <div class="clearfix">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pilih Venue</label>
                                    <select class="custom-select2 form-control" multiple="multiple" name="venue_selects[]">
                                        @foreach ($venues as $venue)
                                            <option value="{{ $venue->id }}"
                                                {{ in_array($venue->id, $selectedVenues ?? []) ? 'selected' : '' }}>Venue
                                                {{ $venue->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Dari Tanggal</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ $startDate }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Hingga Tanggal</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">Cari Data</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="clearfix my-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="badge badge-success"> Total Pemasukan: Rp{{ number_format($totalIncome) }} </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="#" class="btn btn-outline-info mr-3" onclick="printTransactionData()">
                                <i class="icon-copy dw dw-print"></i> Cetak Data Transaksi
                            </a>
                        </div>
                    </div>
                </div>
                <table class="data-table table stripe hover nowrap">
                    <thead>
                        <tr>
                            <th class="table-plus">#</th>
                            <th>Tanggal</th>
                            <th>Jadwal</th>
                            <th>Nama</th>
                            <th>Venue</th>
                            <th>Harga Paket</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$rents->count() > 0)
                            <tr>
                                <td colspan="8">
                                    <div class="alert alert-info text-center">Tidak Ada Transaksi Booking.</div>
                                </td>
                            </tr>
                        @else
                            @foreach ($rents as $rent)
                                <tr>
                                    <td class="table-plus">{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                                    <td>
                                        @if ($rent->formatted_schedule == null)
                                            <div class="badge badge-danger">Tidak ada</div>
                                        @else
                                            {{ $rent->formatted_schedule }}
                                        @endif
                                    </td>
                                    <td>{{ $rent->name }}</td>
                                    <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
                                    </td>
                                    <td>Rp{{ number_format($rent->servicePackageDetail->price) }}</td>
                                    <td text-center>
                                        @if ($rent->formatted_schedule == null)
                                            <span class="badge badge-danger">Jadwal Salah</span>
                                        @else
                                            @if ($rent->rent_status == 0)
                                                <span class="badge badge-info "><i class="icon-copy dw dw-question"></i>
                                                    Diajukan</span>
                                            @elseif ($rent->rent_status == 1)
                                                <span class="badge badge-success "><i class="icon-copy dw dw-checked"></i>
                                                    Dibooking</span>
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
                                                <span class="badge badge-warning "><i class="icon-copy dw dw-money-1"></i>
                                                    Belum Bayar</span>
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
                                    <td class="text-center">
                                        @if ($rent->dp_payment == null)
                                            @if ($rent->dp_price == 0)
                                                <div class="badge badge-danger">Tidak ada DP</div>
                                            @else
                                                <div class="badge badge-warning">DP
                                                    Rp{{ number_format($rent->dp_price) }}</div>
                                            @endif
                                        @else
                                            <div class="badge badge-success">Lunas
                                                Rp{{ number_format($rent->dp_price) }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function printTransactionData() {
            var content = `
            <h4 class="h4 text">Transaction Booking Studio Owner <span class="text-primary">{{ ucfirst(Auth::user()->name) }}</span></h4>
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
                        <th class="table-plus">#</th>
                        <th>Tanggal</th>
                        <th>Jadwal</th>
                        <th>Nama</th>
                        <th>Venue</th>
                        <th>Harga Paket</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$rents->count() > 0)
                        <tr>
                            <td colspan="8">
                                <div class="alert alert-info text-center">Tidak Ada Transaksi Booking.</div>
                            </td>
                        </tr>
                    @else
                        @foreach ($rents as $rent)
                            <tr>
                                <td class="table-plus">{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                                <td>
                                    @if ($rent->formatted_schedule == null)
                                        <div class="badge badge-danger">Tidak ada</div>
                                    @else
                                        {{ $rent->formatted_schedule }}
                                    @endif
                                </td>
                                <td>{{ $rent->name }}</td>
                                <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}</td>
                                <td>Rp{{ number_format($rent->servicePackageDetail->price) }}</td>
                                <td class="text-center">
                                    @if ($rent->formatted_schedule == null)
                                        Jadwal Salah
                                    @else
                                        @if ($rent->rent_status == 0)
                                            Diajukan
                                        @elseif ($rent->rent_status == 1)
                                            Dibooking
                                        @elseif ($rent->rent_status == 2)
                                            Selesai
                                        @elseif ($rent->rent_status == 3)
                                            Ditolak
                                        @elseif ($rent->rent_status == 4)
                                            Expired
                                        @elseif ($rent->rent_status == 5)
                                            Belum Bayar
                                        @elseif ($rent->rent_status == 6)
                                            Sedang Foto
                                        @elseif ($rent->rent_status == 7)
                                            Dibatalkan
                                        @else
                                        Tidak valid
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($rent->dp_payment == null)
                                        @if ($rent->dp_price == 0)
                                            Tidak ada DP
                                        @else
                                            DP (Rp{{ number_format($rent->dp_price) }})
                                        @endif
                                    @else
                                        Lunas (Rp{{ number_format($rent->dp_price) }})
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="clearfix my-2">
                <div class="pull-right">
                    <h3>Total Pembayaran: Rp{{ number_format($totalIncome) }}</h3>
                </div>
            </div>
            `;

            var printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
            <html>
            <head>
                <title>Cetak Data Transaksi</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .badge { font-size: 12px; }
                    .h4 { font-size: 18px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    table, th, td { border: 1px solid black; }
                    th, td { padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .text-center { text-align: center; }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                ${content}
            </body>
            </html>
            `);
            printWindow.document.close();
        }
    </script>
@endsection
{{-- @push('scripts') --}}

{{-- @endpush --}}
