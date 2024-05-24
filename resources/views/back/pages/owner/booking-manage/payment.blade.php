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
                            <th style="width: 170px;">Tanggal Booking</th>
                            <td>{{ $rent->date }}</td>
                        </tr>
                        <tr>
                            <th>Nama Penyewa</th>
                            <td>{{ $rent->name }}</td>
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
                        <tr>
                            <th>Jadwal</th>
                            <td>{{ $rent->formatted_schedule }}</td>
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
                            <th class="ml-4">Metode Pembayaran</th>
                            <td>
                                <div class="badge badge-info mr-2"><i class="fas fa-money"></i> Lunas</div>
                                @if ($rent->servicePackageDetail->servicePackage->dp_status == 1)
                                    & <div class="badge badge-success ml-2"><i class="fas fa-money"></i> DP
                                        {{ $rent->servicePackageDetail->servicePackage->dp_percentage * 100 }}%</div>
                                @elseif ($rent->servicePackageDetail->servicePackage->dp_status == 2)
                                    & <div class="badge badge-success ml-2"><i class="fas fa-money"></i> Min. Bayar
                                        Rp
                                        {{ number_format($rent->servicePackageDetail->servicePackage->dp_min, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
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
                        @method('PATCH')
                        <x-alert.form-alert />

                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="payment_status">Jenis Pembayaran</label><br>
                                        @foreach ($venues as $venue)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="venue" value="{{ $venue->id }}" required>
                                                <label class="form-check-label" for="venue_{{ $venue->id }}">
                                                    {{ $venue->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="name">Metode Pembayaran</label>
                                        select option
                                        <input type="select" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="Contoh: Wisuda 1, Diamond 1"
                                            value="" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="information">Input Bukti Pembayaran</label>
                                        <input type="file" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="Contoh: Wisuda 1, Diamond 1"
                                            value="" required>
                                        @error('information')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
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
