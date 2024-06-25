<div id="print-container" class="print-container">
    <div class="invoice-header p-4">
        <div class="clearfix d-flex justify-content-between align-items-center">
            <div class="pull-left">
                <img src="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->owner->logo }}"
                    alt="Logo {{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->owner->name }}"
                    style="max-width: 150px; height: auto; border-radius: 50%;">
            </div>
            <div class="pull-right text-right">
                <h1>INVOICE</h1><br>
                <h2>{{ $rent->faktur }}</h2>
            </div>
        </div>
    </div>
    <div class="invoice-body p-4">
        <div class="clearfix d-flex justify-content-between align-items-top">
            <div class="pull-left">
                <p><strong>Nama Venue : </strong>
                    {{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
                </p>
                <p><strong>CP Venue : </strong>
                    {{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->phone_number }}</p>
                <p><strong>Alamat Venue : </strong>
                    {{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->venue->address)) }},
                    {{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->venue->village->name)) }},
                    {{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->venue->village->district->name)) }}
                </p>
            </div>
            <div class="pull-right">
                <p><strong>Nama Customer : </strong>
                    {{ $rent->name }}
                </p>
                <p><strong>CP Customer : </strong>
                    {{ $rent->no_hp }}</p>
                <p><strong>Tanggal Booking : </strong> {{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</p>
            </div>
        </div>
        <h2 class="mt-3 ml-2">Rincian Booking</h2>
        <p><strong>Tanggal Booking : </strong> {{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</p>
        <p><strong>Jadwal Booking : </strong> {{ $firstOpeningHour->hour }} - {{ $formattedLastOpeningHour }}</p>
        <p><strong>Nama Paket : </strong> {{ $rent->servicePackageDetail->servicePackage->name }}
            ({{ $rent->servicePackageDetail->sum_person }} Orang)</p>
        <p><strong>Lama Pemotretan : </strong>
            @if ($rent->servicePackageDetail->time_status == 0)
                30 Menit
            @elseif($rent->servicePackageDetail->time_status == 1)
                60 Menit
            @elseif($rent->servicePackageDetail->time_status == 2)
                90 Menit
            @elseif($rent->servicePackageDetail->time_status == 3)
                120 Menit
            @else
                Tidak Valid
            @endif
        </p>
        <p><strong>Harga Paket : </strong> Rp{{ number_format($rent->total_price) }}</p>


        <h4 class="mt-3 ml-2">Rincian Paket Foto</h4>
        <h6 class="mt-3 ml-2">A Add On</h6>
        @if ($rent->servicePackageDetail->servicePackage->addOnPackageDetails->count() > 0)
            <table class="invoice-table">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
                @foreach ($rent->servicePackageDetail->servicePackage->addOnPackageDetails as $addOnPackageDetail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $addOnPackageDetail->addOnPackage->name }}</td>
                        <td>{{ $addOnPackageDetail->sum }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>Tidak Include Add On</p>
        @endif
        <h6 class="mt-3 ml-2">B Cetak Foto</h6>
        @if ($rent->servicePackageDetail->servicePackage->printPhotoDetails->count() > 0)
            <table class="invoice-table">
                <tr>
                    <th>Ukuran</th>
                    <td>
                        @foreach ($rent->servicePackageDetail->servicePackage->printPhotoDetails as $printPhotoDetail)
                            (Size {{ $printPhotoDetail->printPhoto->size }})
                        @endforeach
                    </td>
                </tr>
            </table>
        @else
            <p>Tidak Include Cetak Foto</p>
        @endif

        <h6 class="mt-3 ml-2">C Frame Foto</h6>
        @if ($rent->servicePackageDetail->servicePackage->framePhotoDetails->count() > 0)
            <table class="invoice-table">
                <tr>
                    <th>Ukuran</th>
                    <td>
                        @foreach ($rent->servicePackageDetail->servicePackage->framePhotoDetails as $framePhotoDetail)
                            (Size {{ $framePhotoDetail->printPhoto->size }})
                        @endforeach
                    </td>
                </tr>
            </table>
        @else
            <p>Tidak Include Frame Foto</p>
        @endif
        <h2 class="mt-3 ml-2">Pembayaran Booking</h2>
        <table class="invoice-table">
            <tr>
                <th>Jenis Pembayaran</th>
                <th>Tanggal Pembayaran</th>
                <th>Harga Paket</th>
                <th>Nominal</th>
                <th>Status Pembayaran</th>
            </tr>
            @if ($rent->dp_price > 0)
                <tr>
                    <td>Pembayaran Awal (Dp)</td>
                    <td>{{ \Carbon\Carbon::parse($rent->dp_price_date)->format('H:i:s, d M Y') }}</td>
                    <td>Rp{{ number_format($rent->total_price) }}</td>
                    <td>Rp{{ number_format($rent->dp_price) }}</td>
                    <td>
                        @if ($rent->dp_payment != null && $rent->dp_price == $rent->total_price)
                            Lunas
                        @else
                            Belum Lunas
                        @endif
                    </td>
                </tr>
            @endif
            @if ($rent->dp_payment != null && $rent->dp_price != $rent->total_price)
                <tr>
                    <td>Pelunasan</td>
                    <td>{{ \Carbon\Carbon::parse($rent->dp_payment)->format('H:i:s, d M Y') }}</td>
                    <td>Rp{{ number_format($rent->total_price) }}</td>
                    <td>Rp{{ number_format($rent->total_price - $rent->dp_price) }}</td>
                    <td>
                        Lunas
                    </td>
                </tr>
            @endif
        </table>
        <div class="invoice-summary">
            <h1 class="mt-3 ml-2">Sisa Pembayaran</h1>
            <table>
                @if ($rent->dp_payment == null)
                    <tr>
                        <th>Pembayaran</th>
                        <td>
                            <h1 style="color:red;">Belum Lunas</h1>
                        </td>
                    </tr>
                    <tr>
                        <th>Dp Awal</th>
                        <td>
                            Rp{{ number_format($rent->dp_price) }}
                            Pada <b>{{ \Carbon\Carbon::parse($rent->dp_price_date)->format('H:i:s, d M Y') }}</b>
                        </td>
                    </tr>
                    <tr>
                        <th>Sisa Pembayaran</th>
                        <td> Rp{{ number_format($rent->total_price - $rent->dp_price) }}</td>
                    </tr>
                @else
                    <tr>
                        <th>Pembayaran</th>
                        <td>
                            <h1 style="color:green;">Lunas</h1>
                        </td>
                    </tr>
                    <tr>
                        <th>Dp Awal</th>
                        <td>
                            Rp{{ number_format($rent->dp_price) }}
                            Pada <b>{{ \Carbon\Carbon::parse($rent->dp_price_date)->format('H:i:s, d M Y') }}</b>
                        </td>
                    </tr>
                    <tr>
                        <th>Pelunasan</th>
                        <td>
                            Rp{{ number_format($rent->total_price - $rent->dp_price) }}
                            Pada <b>{{ \Carbon\Carbon::parse($rent->dp_payment)->format('H:i:s, d M Y') }}</b>
                        </td>
                    </tr>
                @endif

            </table>
        </div>
    </div>
</div>
