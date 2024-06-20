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
                <div class="clearfix">
                    <div class="pull-right">
                        <a href="" class="btn btn-outline-success"><i class="icon-copy dw dw-photo-camera-1"></i>
                            Mulai Foto</a>
                        <a href="" class="btn btn-outline-success"><i class="icon-copy dw dw-photo-camera-1"></i>
                            Pelunasan</a>
                        <a href="" class="btn btn-outline-success"><i class="icon-copy dw dw-photo-camera-1"></i>
                            Selesai Foto</a>
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
                            <p><b>{{ ucwords($rent->name) }}</b> Telah membooking Venue
                                <b>{{ ucwords($rent->servicePackageDetail->servicePackage->serviceEvent->venue->name) }}</b>
                                yang berlokasi di
                                <b>{{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->venue->address)) }},{{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->venue->village->name)) }},{{ ucwords(strtolower($rent->servicePackageDetail->servicePackage->serviceEvent->venue->village->district->name)) }}</b>
                                <br>Pada Tanggal <b>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</b> dengan
                                Jadwal
                                Foto dari Pukul <b>{{ $rent->formatted_schedule }}</b> Hingga Pukul .<br>Untuk Paket foto
                                yang dipesan adalah
                                <b>{{ $rent->servicePackageDetail->servicePackage->name }}</b> dengan total Orang yang foto
                                <b>{{ $rent->servicePackageDetail->sum_person }} Orang</b> & lama pemotretannya
                                @if ($rent->servicePackageDetail->time_status == 0)
                                    <b>30 Menit.</b>
                                @elseif($rent->servicePackageDetail->time_status == 1)
                                    <b>60 Menit.</b>
                                @elseif($rent->servicePackageDetail->time_status == 2)
                                    <b>90 Menit.</b>
                                @elseif($rent->servicePackageDetail->time_status == 3)
                                    <b>120 Menit.</b>
                                @else
                                    <b>Tidak Valid</b>
                                @endif
                                <br> Booking Foto ini
                                @if ($rent->dp_price == 0)
                                    <b class="badge badge-danger">Belum membayar Dp awal</b>
                                @elseif($rent->dp_price < $rent->total_price)
                                    Baru membayar<b class="badge badge-warning">Dp awal Rp
                                        {{ number_format($rent->dp_price) }}</b>
                                @elseif($rent->dp_price == $rent->total_price)
                                    <b class="badge badge-success">Telah Lunas</b>
                                @else
                                    <b>Tidak Valid</b>
                                @endif
                                Dengan Total Harga <b class="badge badge-success">Rp
                                    {{ number_format($rent->total_price, 0, ',', '.') }}</b>
                                <br>Berikut Detail Dari Jadwal Booking & Paket
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
                                            {{ $firstOpeningHour->hour }}</div> - <div class="badge badge-primary"><i
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
                                            <div class="badge badge-danger"><i class="icon-copy dw dw-money-1"></i> Rp
                                                {{ number_format($rent->dp_price, 0, ',', '.') }}</div>
                                        @elseif($rent->dp_price < $rent->total_price)
                                            <div class="badge badge-warning"><i class="icon-copy dw dw-money-1"></i> Rp
                                                {{ number_format($rent->dp_price, 0, ',', '.') }}</div>
                                        @elseif($rent->dp_price == $rent->total_price)
                                            <div class="badge badge-success"><i class="icon-copy dw dw-money-1"></i> Rp
                                                {{ number_format($rent->dp_price, 0, ',', '.') }}</div>
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

                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
