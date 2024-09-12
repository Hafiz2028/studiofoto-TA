<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Cetak Data Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .badge {
            font-size: 12px;
        }

        .h4 {
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 100px;
        }

        @media print {
            @page {
                size: landscape;
                margin: 20mm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                page-break-after: avoid;
            }

            .header img {
                max-height: 80px;
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</head>

<body>
    <div class="header">
        <div>
            <h2>Transaction Booking Studio</h2>
            <h4>Owner {{ ucfirst(Auth::user()->name) }}</h4>
            <h5>Studio yang dipilih : {{ $selectedVenues }}</h5>
            @if ($startDate != null)
                <h5>Dari Tanggal : {{ \Carbon\Carbon::parse($startDate)->format('j F Y') }}</h5>
            @endif
            @if ($endDate != null)
                <h5>Hingga Tanggal : {{ \Carbon\Carbon::parse($endDate)->format('j F Y') }}</h5>
            @endif

        </div>
        <div>
            <img src="{{ Auth::user()->owner->logo }}" alt="Logo {{ Auth::user()->owner->name }}">
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
            @forelse ($rents as $index => $rent)
                <tr>
                    <td class="table-plus">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                    <td>{{ $rent->formatted_schedule ?: '<div class="badge badge-danger">Tidak ada</div>' }}</td>
                    <td>{{ $rent->name }}</td>
                    <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}</td>
                    <td>Rp {{ number_format($rent->servicePackageDetail->price) }}</td>
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
                                Tidak Valid
                            @endif
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($rent->dp_payment == null)
                            @if ($rent->dp_price == 0)
                                Tidak ada DP
                            @else
                                DP (Rp {{ number_format($rent->dp_price) }})
                            @endif
                        @else
                            Lunas (Rp {{ number_format($rent->dp_price) }})
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="alert alert-info text-center">Tidak Ada Transaksi Booking.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="clearfix my-2">
        <div class="pull-right">
            <h3>Total Pemasukan: Rp {{ number_format($totalIncome) }}</h3>
        </div>
    </div>
</body>

</html>
