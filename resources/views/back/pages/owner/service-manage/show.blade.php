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
                        @if (auth()->check() && auth()->user()->role === 'owner')
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
                        @if (auth()->check() && auth()->user()->role === 'admin')
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

    @if (auth()->check() && auth()->user()->role === 'owner')
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
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <div class="card card-primary shadow">
                        <div class="card-header bg-info text-white">
                            <h3 class="text-white mb-0 text-center">Detail Layanan</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
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
                                    <h6 class="mb-3 mt-3">
                                        <span style="display: inline-block; width: 170px;">Katalog Harga Layanan</span>
                                        :
                                    </h6>
                                    <div class="card-body text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="photo-display">
                                                @if (!empty($service->catalog))
                                                    <img src="/images/venues/Katalog/{{ $service->catalog }}"
                                                        alt="Katalog Image"
                                                        style="width: 100%; max-width: 200px; height: 281px;"
                                                        class="document-display mb-2" id="imbDocument">
                                                @else
                                                    <img src="/images/venues/IMB/default-surat.png"
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

                <div class="col-lg-7 col-md-12 col-sm-12">
                    <div class="card card-primary shadow">
                        <div class="card-header bg-info text-white">
                            <h2 class="text-center text-white mb-3">List Paket Foto Layanan</h2>
                        </div>
                        <div class="card-body">
                            <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr>
                                        <th class="table-plus">#</th>
                                        <th>Nama Paket</th>
                                        <th>Metode Pembayaran</th>
                                        @if (auth()->check() && auth()->user()->role === 'owner')
                                            <th class="datatable-nosort">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($packages->count() > 0)
                                        @foreach ($packages as $index => $package)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $package->name }}</td>
                                                <td class="align-middle text-center">
                                                    @if ($package->dp_status !== null)
                                                        @switch($package->dp_status)
                                                            @case(0)
                                                                <span class="badge badge-info" style="font-size:small;">Lunas</span>
                                                            @break

                                                            @case(1)
                                                                <span class="badge badge-success" style="font-size:small;">DP
                                                                    {{ $package->dp_percentage * 100 }} %</span>
                                                            @break

                                                            @case(2)
                                                                <span class="badge badge-success" style="font-size:small;">Min.
                                                                    Bayar Rp
                                                                    {{ number_format($package->dp_min, 0, ',', '.') }}</span>
                                                            @break

                                                            @default
                                                                <span class="badge badge-danger" style="font-size:small;">Tidak
                                                                    Valid</span>
                                                        @endswitch
                                                    @else
                                                        <span class="badge badge-danger" style="font-size:small;">Belum
                                                            Diatur</span>
                                                    @endif
                                                </td>
                                                @if (auth()->check() && auth()->user()->role === 'owner')
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                                href="#" role="button" data-toggle="dropdown">
                                                                <i class="dw dw-more"></i>
                                                            </a>
                                                            <div
                                                                class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                                <a href="javascript:;" class="dropdown-item text-primary"
                                                                    onclick="showPackageDetail({{ $package->id }}, '{{ $venue->id }}', '{{ $service->id }}')"
                                                                    data-venue="{{ $venue->id }}"
                                                                    data-service="{{ $service->id }}">
                                                                    <i class="dw dw-eye"></i>
                                                                    Detail
                                                                </a>
                                                                <a href="{{ route('owner.venue.services.packages.edit', ['venue' => $venue->id, 'service' => $service->id, 'package' => $package->id]) }}"
                                                                    class="dropdown-item text-info">
                                                                    <i class="dw dw-edit2"></i> Edit
                                                                </a>
                                                                <a href="javascript:;"
                                                                    class="dropdown-item text-danger deletePackageBtn"
                                                                    data-package-id="{{ $package->id }}">
                                                                    <i class="icon-copy dw dw-delete-3"></i> Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="modal fade" id="detailPackageModal" tabindex="-1"
                                                            role="dialog" aria-labelledby="detailPackageModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-xl modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-info text-white">
                                                                        <h5 class="modal-title text-white"
                                                                            id="detailPackageModalLabel">
                                                                            Detail <span id="packageName"></span>
                                                                            <i class="fas fa-info-circle ml-2"></i>
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="container-fluid">
                                                                            <div class="row">
                                                                                <div class="col-md-7 mb-4">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <h6 class="card-title">
                                                                                                Detail Paket
                                                                                                Layanan</h6>
                                                                                            <hr>
                                                                                            <div id="servicePackageList"
                                                                                                class="table-responsive">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-5">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <h6 class="card-title">
                                                                                                Informasi
                                                                                                Paket
                                                                                            </h6>
                                                                                            <hr>
                                                                                            <div id="packageInfo">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <h6 class="card-title">
                                                                                                Cetak Foto</h6>
                                                                                            <hr>
                                                                                            <div id="printPhotoList"
                                                                                                class="table-responsive">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <h6 class="card-title">
                                                                                                Frame Foto</h6>
                                                                                            <hr>
                                                                                            <div id="framePhotoList"
                                                                                                class="table-responsive">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <h6 class="card-title">
                                                                                                Add On</h6>
                                                                                            <hr>
                                                                                            <div id="addOnPackageList"
                                                                                                class="table-responsive">
                                                                                            </div>
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
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">
                                                <div class="alert alert-info text-center">Tidak Ada List Paket
                                                    Harga.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header bg-info">
                    <h2 class="card-title text-center text-white">Foto Layanan</h2>
                </div>
                <div class="card-body d-flex flex-wrap justify-content-center align-items-center">
                    @if ($serviceImages->isEmpty())
                        <div class="alert alert-info text-center w-100">Tidak Ada Foto Layanan</div>
                    @else
                        <div class="d-flex flex-wrap justify-content-center">
                            @foreach ($serviceImages as $index => $image)
                                <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                    <img src="/images/venues/Service_Image/{{ $image->image }}"
                                        alt="{{ $image->image }}" class="card-img">
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
    {{-- detail paket --}}
    <script>
        function showPackageDetail(packageId, venue, service) {
            var ajaxUrl =
                '{{ route('owner.venue.services.packages.showDetail', ['venue' => ':venue', 'service' => ':service', 'package' => ':package']) }}';
            ajaxUrl = ajaxUrl.replace(':venue', venue).replace(':service', service).replace(':package', packageId);

            console.log('AJAX URL:', ajaxUrl);

            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.information) {
                        $('#packageInfo').html('<p>' + response.information.replace(/\n/g, '<br>') + '</p>');
                    } else {
                        $('#packageInfo').html('<p class="alert alert-info">Tidak ada Deskripsi Paket.</p>');
                    }
                    $('#packageName').text(response.packageName);
                    $('#printPhotoList').empty();
                    $('#servicePackageList').empty();
                    $('#framePhotoList').empty();
                    $('#addOnPackageList').empty();

                    if (response.printPhotoDetails && response.printPhotoDetails.length > 0) {
                        var printPhotoTableHtml = '<table class="table">';
                        printPhotoTableHtml += '<thead>';
                        printPhotoTableHtml += '<tr>';
                        printPhotoTableHtml += '<th scope="col">Size</th>';
                        printPhotoTableHtml += '</tr>';
                        printPhotoTableHtml += '</thead>';
                        printPhotoTableHtml += '<tbody>';
                        response.printPhotoDetails.forEach(function(printPhoto) {
                            printPhotoTableHtml += '<tr>';
                            printPhotoTableHtml += '<td>' + printPhoto.size + '</td>';
                            printPhotoTableHtml += '</tr>';
                        });
                        printPhotoTableHtml += '</tbody>';
                        printPhotoTableHtml += '</table>';
                        $('#printPhotoList').append(printPhotoTableHtml);
                    } else {
                        $('#printPhotoList').html(
                            '<p class="alert alert-info">Paket ini tidak Include cetak foto.</p>');
                    }

                    if (response.servicePackageDetails) {
                        var servicePackageTableHtml = '<table class="table">';
                        servicePackageTableHtml += '<thead>';
                        servicePackageTableHtml += '<tr>';
                        servicePackageTableHtml +=
                            '<th class="align-middle text-center" scope="col">Jumlah Orang</th>';
                        servicePackageTableHtml +=
                            '<th class="align-middle text-center" scope="col">Waktu Pemotretan</th>';
                        servicePackageTableHtml +=
                            '<th class="align-middle text-center" scope="col">Harga</th>';

                        servicePackageTableHtml += '</tr>';
                        servicePackageTableHtml += '</thead>';
                        servicePackageTableHtml += '<tbody>';
                        response.servicePackageDetails.forEach(function(servicePackageDetail) {
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(servicePackageDetail.price);
                            var timeBadgeClass = '';
                            var timeText = '';

                            if (servicePackageDetail.time_status === 0) {
                                timeText = '30 Menit ';
                                timeBadgeClass = 'badge badge-success';
                            } else if (servicePackageDetail.time_status === 1) {
                                timeText = '60 Menit';
                                timeBadgeClass = 'badge badge-primary';
                            } else if (servicePackageDetail.time_status === 2) {
                                timeText = '90 Menit';
                                timeBadgeClass = 'badge badge-info';
                            } else if (servicePackageDetail.time_status === 3) {
                                timeText = '120 Menit';
                                timeBadgeClass = 'badge badge-warning';
                            } else {
                                timeText = 'Tidak Valid'
                                timeBadgeClass = 'badge badge-danger';
                            }
                            servicePackageTableHtml += '<tr>';
                            servicePackageTableHtml += '<td>' + servicePackageDetail.sum_person +
                                ' Orang' +
                                '</td>';
                            servicePackageTableHtml +=
                                '<td class="align-middle text-center"><span class="' + timeBadgeClass +
                                '">' +
                                timeText + '</span></td>';
                            servicePackageTableHtml += '<td>' + formattedPrice + '</td>';
                            servicePackageTableHtml += '</tr>';
                        });
                        servicePackageTableHtml += '</tbody>';
                        servicePackageTableHtml += '</table>';
                        $('#servicePackageList').append(servicePackageTableHtml);
                    }
                    if (response.framePhotoDetails && response.framePhotoDetails.length > 0) {
                        var framePhotoTableHtml = '<table class="table">';
                        framePhotoTableHtml += '<thead>';
                        framePhotoTableHtml += '<tr>';
                        framePhotoTableHtml += '<th scope="col">Size</th>';
                        framePhotoTableHtml += '</tr>';
                        framePhotoTableHtml += '</thead>';
                        framePhotoTableHtml += '<tbody>';
                        response.framePhotoDetails.forEach(function(framePhoto) {
                            framePhotoTableHtml += '<tr>';
                            framePhotoTableHtml += '<td>' + framePhoto.size + '</td>';
                            framePhotoTableHtml += '</tr>';
                        });
                        framePhotoTableHtml += '</tbody>';
                        framePhotoTableHtml += '</table>';
                        $('#framePhotoList').append(framePhotoTableHtml);
                    } else {
                        $('#framePhotoList').html(
                            '<p class="alert alert-info">Paket ini tidak Include Frame Foto.</p>');
                    }
                    if (response.addOnPackageDetails && response.addOnPackageDetails.length > 0) {
                        var addOnPackageTableHtml = '<table class="table">';
                        addOnPackageTableHtml += '<thead>';
                        addOnPackageTableHtml += '<tr>';
                        addOnPackageTableHtml += '<th scope="col">Nama Paket Tambahan</th>';
                        addOnPackageTableHtml += '<th scope="col">Jumlah</th>';
                        addOnPackageTableHtml += '</tr>';
                        addOnPackageTableHtml += '</thead>';
                        addOnPackageTableHtml += '<tbody>';
                        response.addOnPackageDetails.forEach(function(addOnPackage) {
                            addOnPackageTableHtml += '<tr>';
                            addOnPackageTableHtml += '<td>' + addOnPackage.name + '</td>';
                            addOnPackageTableHtml += '<td>' + addOnPackage.sum + '</td>';
                            addOnPackageTableHtml += '</tr>';
                        });
                        addOnPackageTableHtml += '</tbody>';
                        addOnPackageTableHtml += '</table>';
                        $('#addOnPackageList').append(addOnPackageTableHtml);
                    } else {
                        $('#addOnPackageList').html(
                            '<p class="alert alert-info">Paket ini tidak Include Add On.</p>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error occurred! Please check the console for more details.');
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
