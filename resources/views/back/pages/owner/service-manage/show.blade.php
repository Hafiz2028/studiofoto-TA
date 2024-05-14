@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Detail Layanan')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Detail Service</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        @if (auth()->guard('owner')->check())
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
                                Detail Service
                            </li>
                        @endif
                        @if (auth()->guard('admin')->check())
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.home') }}">Home</a>
                            </li>
                            @if ($venue->status == 0)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.venue.need-approval') }}">Need Approval Venue</a>
                                </li>
                            @endif
                            @if ($venue->status == 1)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.venue.approved') }}">Approved Venue</a>
                                </li>
                            @endif
                            @if ($venue->status == 2)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.venue.rejected') }}">Rejected Venue</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.venue.show', $venue->id) }}">Detail Venue</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Detail Layanan
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (auth()->guard('owner')->check())
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card-box p-2">
                    <div class="d-flex justify-content-end align-items-center flex-column flex-sm-row">
                        <div class="actions-buttons">
                            <a href="javascript:;"
                                class="btn btn-outline-danger btn-lg deleteServiceBtn mr-2 mr-sm-1 mb-2 mb-sm-0"
                                data-service-id="{{ $service->id }}">
                                <i class="fas fa-trash"></i> Hapus Layanan
                            </a>
                            <a href="{{ route('owner.venue.services.edit', ['venue' => $venue->id, 'service' => $service->id]) }}"
                                class="btn btn-outline-info mr-2 mr-sm-1 mb-2 mb-sm-0">
                                <i class="fas fa-edit"></i> Update Layanan
                            </a>
                            <a href="{{ route('owner.venue.services.packages.create', ['venue' => $venue->id, 'service' => $service->id]) }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus"></i> Paket Foto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                                        <span style="display: inline-block; width: 170px;">Katalog Harga Layanan</span>
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
                                                <span class="text-nowrap">Tidak Ada Katalog.</span>
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
                                                <div class="alert alert-info text-center">Tidak Ada Cetak Foto Layanan
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
                    <div class="card-body pt-0">
                        @if (auth()->guard('owner')->check())
                            <a href="{{ route('owner.venue.services.packages.create', ['venue' => $venue->id, 'service' => $service->id]) }}"
                                class="btn btn-primary float-right my-3">
                                <i class="fas fa-plus"></i> Paket Foto
                            </a>
                        @endif
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Paket</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Harga</th>
                                    <th>Add On</th>
                                    <th>Waktu Foto</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($packages->count() > 0)
                                    @foreach ($packages as $index => $package)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $package->name }}</td>
                                            <td class="align-middle text-center">
                                                @switch($package->dp_status)
                                                    @case(0)
                                                        <span class="badge badge-secondary">Tidak terima DP</span>
                                                    @break

                                                    @case(1)
                                                        <span class="badge badge-success">DP
                                                            {{ number_format($package->dp_percentage * 100) }}%</span>
                                                    @break

                                                    @case(2)
                                                        <span class="badge badge-success">Min. Bayar Rp
                                                            {{ number_format($package->dp_percentage * $package->price, 0, ',', ' ') }}</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-danger">Tidak Valid</span>
                                                @endswitch
                                            </td>
                                            <td>Rp {{ number_format($package->price, 0, ',', ' ') }}</td>
                                            <td style="text-align: justify;">
                                                @if ($package->addOnPackageDetails->isNotEmpty())
                                                    @foreach ($package->addOnPackageDetails as $index => $addOnDetail)
                                                        {{ $addOnDetail->sum }} {{ $addOnDetail->addOnPackage->name }}
                                                        @if (!$loop->last)
                                                            <br>&
                                                        @endif
                                                    @endforeach
                                                @else
                                                    Tidak ada
                                                @endif
                                            </td>
                                            <td>
                                                @switch($package->time_status)
                                                    @case(0)
                                                        30 Menit
                                                    @break

                                                    @case(1)
                                                        60 Menit
                                                    @break

                                                    @case(2)
                                                        90 Menit
                                                    @break

                                                    @case(3)
                                                        120 Menit
                                                    @break

                                                    @default
                                                        Tidak Valid
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-outline-secondary btn-lg"
                                                    onclick="showPackageDetail({{ $package->id }})" style="width: 50px;"
                                                    data-toggle="tooltip" data-placement="auto" title="Detail Paket">
                                                    <i class="fas fa-info"></i>
                                                </a>
                                                <div class="modal fade" id="detailPackageModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="detailPackageModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered"
                                                        role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-info text-white">
                                                                <h5 class="modal-title text-white"
                                                                    id="detailPackageModalLabel">
                                                                    Detail {{ $package->name }} <i
                                                                        class="fas fa-info-circle ml-2"></i>
                                                                </h5>
                                                                <button type="button" class="close text-white"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <h6 class="card-title">Informasi
                                                                                        Paket
                                                                                    </h6>
                                                                                    <hr>
                                                                                    <div id="packageInfo"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <h6 class="card-title">Daftar Cetak
                                                                                        Foto</h6>
                                                                                    <hr>
                                                                                    <ul id="printPhotoList"
                                                                                        class="list-group"></ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('owner.venue.services.packages.edit', ['venue' => $venue->id, 'service' => $service->id, 'package' => $package->id]) }}"
                                                    style="width: 50px;" class="btn btn-outline-info btn-lg"
                                                    data-toggle="tooltip" data-placement="auto" title="Edit Paket">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:;"
                                                    class="btn btn-outline-danger btn-lg deletePackageBtn"
                                                    data-package-id="{{ $package->id }}" style="width: 50px;">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="alert alert-info text-center">Tidak Ada List Paket Harga.</div>
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
                <div class="card-body d-flex flex-wrap justify-content-center align-items-start">
                    @if ($serviceImages->isEmpty())
                        <div class="alert alert-info text-center w-100">Tidak Ada Foto Layanan</div>
                    @else
                        <div class="d-flex flex-wrap justify-content-start">
                            @foreach ($serviceImages as $index => $image)
                                <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                    <img src="/images/venues/Service_Image/{{ $image->image }}"
                                        alt="{{ $image->image }}" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection
@push('stylesheets')
@endpush
@push('scripts')
    <script>
        function showPackageDetail(packageId) {
            $.ajax({
                url: '{{ route('owner.venue.services.packages.showDetail', ['venue' => $venue->id, 'service' => $service->id, 'package' => ':package']) }}'
                    .replace(':package', packageId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#packageInfo').html('<p>' + response.information + '</p>');

                    // Kosongkan tabel sebelum menambahkan data
                    $('#printPhotoList').empty();

                    // Buat header tabel
                    var tableHtml = '<table class="table">';
                    tableHtml += '<thead>';
                    tableHtml += '<tr>';
                    tableHtml += '<th scope="col">Size</th>';
                    tableHtml += '<th scope="col">Harga (Rp)</th>';
                    tableHtml += '</tr>';
                    tableHtml += '</thead>';
                    tableHtml += '<tbody>';

                    // Tambahkan baris untuk setiap cetak foto
                    response.printPhotoDetails.forEach(function(printPhoto) {
                        var formattedPrice = printPhoto.price.toLocaleString('id-ID');
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + printPhoto.size + '</td>';
                        tableHtml += '<td>' + formattedPrice + '</td>';
                        tableHtml += '</tr>';
                    });

                    tableHtml += '</tbody>';
                    tableHtml += '</table>';

                    // Tambahkan tabel ke dalam elemen dengan ID printPhotoList
                    $('#printPhotoList').append(tableHtml);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            $('#detailPackageModal').modal('show');
        }
    </script>
    {{-- modal hapus paket --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.deletePackageBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const packageId = this.getAttribute('data-package-id');
                    Swal.fire({
                        title: 'Hapus Package',
                        text: "Apakah Anda yakin ingin menghapus Paket ini? Paket yang dihapus tidak bisa dikembalikan.",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Batal',
                        cancelButtonColor: '#28a745',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('owner.venue.services.packages.destroy', ['venue' => $venue->id, 'service' => $service->id, 'package' => ':package']) }}"
                                .replace(':package', packageId), {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).then(response => {
                                if (response.ok) {
                                    return response.json();
                                } else {
                                    throw new Error('Gagal menghapus paket');
                                }
                            }).then(data => {
                                Swal.fire({
                                    title: 'Hapus Package',
                                    text: "Paket Berhasil dihapus.",
                                    icon: 'success',
                                    showConfirmButton: true,
                                    timer: 3000,
                                    timerProgressBar: true,
                                }).then(() => {
                                    window.location.reload();
                                });
                            }).catch(error => {
                                console.error('Terjadi kesalahan:', error);
                                Swal.fire({
                                    title: 'Gagal Hapus Package',
                                    text: "Terjadi kesalahan saat menghapus paket.",
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>
    {{-- modal hapus layanan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.deleteServiceBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const serviceId = this.getAttribute('data-service-id');
                    Swal.fire({
                        title: 'Hapus Service',
                        text: "Apakah Anda yakin ingin menghapus Layanan ini? Layanan yang dihapus tidak bisa dikembalikan.",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Batal',
                        cancelButtonColor: '#28a745',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('owner.venue.services.destroy', ['venue' => $venue->id, 'service' => ':service']) }}"
                                .replace(':service', serviceId), {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).then(response => {
                                if (response.ok) {
                                    return response.json();
                                } else {
                                    throw new Error('Gagal menghapus paket');
                                }
                            }).then(data => {
                                Swal.fire({
                                    title: 'Hapus Service',
                                    text: "Layanan Berhasil dihapus.",
                                    icon: 'success',
                                    showConfirmButton: true,
                                    timer: 3000,
                                    timerProgressBar: true,
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('owner.venue.show', $venue->id) }}";
                                });
                            }).catch(error => {
                                console.error('Terjadi kesalahan:', error);
                                Swal.fire({
                                    title: 'Gagal Hapus Service',
                                    text: "Terjadi kesalahan saat menghapus paket.",
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>
@endpush
