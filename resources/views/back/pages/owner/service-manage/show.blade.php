@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Detail Layanan')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Detail Layanan</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.venue.index') }}">Venue's Manage</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.venue.show', $venue->id) }}">Detail Venue</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail Layanan
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card-box">
                <div class="d-flex justify-content-end align-items-center flex-column flex-sm-row">
                    <div class="actions-buttons">
                        <a href="route" class="btn btn-outline-danger mr-2 mr-sm-1 mb-2 mb-sm-0">
                            <i class="fas fa-trash"></i> Hapus Layanan
                        </a>
                        <a href="{{ route('owner.venue.services.edit', ['venue' => $venue->id, 'service' => $service->id]) }}"
                            class="btn btn-outline-info mr-2 mr-sm-1 mb-2 mb-sm-0">
                            <i class="fas fa-edit"></i> Update Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="product-wrap">
        <div class="product-detail-wrap mb-30">
            <div class="row mb-3">
                {{-- card detail venue --}}
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="card card-primary shadow">
                        <div class="card-header bg-info text-white">
                            <h3 class="text-white mb-0 text-center">Detail Layanan</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-2">
                                        <span style="display: inline-block; width: 135px;">Nama Layanan</span>
                                        : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                            {{ ucwords($service->name) }}</p>
                                    </h6>
                                    <h6 class="mb-2">
                                        <span style="display: inline-block; width: 135px;">Tipe Layanan</span>
                                        : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                            {{ ucwords($service->serviceType->service_name) }}</p>
                                    </h6>
                                    <h6 class="mb-3 mt-3">
                                        <span style="display: inline-block; width: 135px;">Deskripsi</span>
                                        :
                                    </h6>
                                    @if ($service->description)
                                        <p class="ml-3" style="text-align: justify;">
                                            {{ ucfirst($service->description) }}
                                        </p>
                                    @else
                                        <div class="alert alert-secondary ml-3">Tidak ada deskripsi layanan.</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3 mt-3">
                                        <span style="display: inline-block; width: 170px;">Paket Harga Layanan</span>
                                        :
                                    </h6>
                                    <div class="card-body text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="photo-display">
                                                @if (!empty($service->catalog))
                                                    <img src="http://studiofoto.test/images/venues/Katalog/{{ $service->catalog }}"
                                                        alt="Katalog Image"
                                                        style="width: 100%; max-width: 200px; height: 281px;"
                                                        class="document-display mb-2" id="imbDocument">
                                                @else
                                                    <img src="http://studiofoto.test/images/venues/IMB/default-surat.png"
                                                        alt="Placeholder Image"
                                                        style="width: 100%; max-width: 200px; height: 281px;">
                                                @endif
                                            </div>
                                        </div>
                                        @if (!empty($service->catalog))
                                            <div class="alert alert-success mt-2" role="alert">
                                                <span class="text-nowrap">Ada</span>
                                            </div>
                                        @else
                                            <div class="alert alert-danger mt-2" role="alert">
                                                <span class="text-nowrap">Tidak Ada</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-12">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h2 class="card-title text-center text-white mb-3">Paket Foto Layanan</h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Ukuran Foto</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($printServiceEvents->count() > 0)
                                        @foreach ($printServiceEvents as $index => $printServiceEvent)
                                            <tr>
                                                <td>{{ $printServiceEvent->printPhoto->size }}</td>
                                                <td>Rp {{ number_format($printServiceEvent->price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2">
                                                <div class="alert alert-danger text-center">Tidak Ada Paket Foto Layanan Ini
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <div class="card shadow mb-3">
                    <div class="card-header bg-info">
                        <h2 class="card-title text-center text-white mb-0">List Paket Foto Layanan</h2>
                    </div>
                    <div class="card-body d-flex justify-content-end"> <!-- Tambahkan class d-flex justify-content-end -->
                        <a href="{{ route('owner.venue.services.packages.create', ['venue' => $venue->id, 'service' => $service->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-plus"></i> Paket Foto
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Paket</th>
                                    <th>Tipe Layanan</th>
                                    <th>Minimal Pembayaran</th>
                                    <th>Harga</th>
                                    <th>Waktu</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($packages->count() > 0)
                                    @foreach ($packages as $index => $package)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $package->name }}</td>
                                            <td>{{ $package->tipe_layanan }}</td>
                                            <td>{{ $package->dp_percentage }}</td>
                                            <td>{{ $package->price }}</td>
                                            <td>{{ $package->time_status }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="alert alert-danger text-center">Tidak Ada List Paket Harga Layanan
                                                Ini</div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-info">
                    <h2 class="card-title text-center text-white">Foto Layanan</h2>
                </div>
                <div class="card-body d-flex flex-wrap justify-content-start align-items-start">
                    @if ($serviceImages->isEmpty())
                        <div class="alert alert-danger text-center">Tidak Ada Foto Layanan</div>
                    @else
                        @foreach ($serviceImages as $index => $image)
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <img src="/images/venues/Service_Image/{{ $image->image }}" alt="{{ $image->image }}"
                                    class="img-fluid rounded">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection
@push('stylesheets')
@endpush
@push('scripts')
@endpush
