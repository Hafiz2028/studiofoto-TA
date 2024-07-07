@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Booking List')
@section('content')
    <style>
        .truncate {
            max-width: 250px;
            /* Sesuaikan panjang yang diinginkan */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/front/img/tomat.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Booking List</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Booking List</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        {{-- <div class="container"> --}}
        <div class="row" style="padding: 0 50px;margin-top: 20px">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix mb-3">
                        <x-alert.form-alert />
                        {{-- <div class="pull-left">
                        <h4 class="h4 text">List Booking Studio <span
                                class="text-primary">{{ ucfirst(Auth::user()->name) }}</span></h4>
                    </div> --}}
                        {{-- <div class="pull-right">
                        <button type="button" class="btn btn-primary exclude-alert" data-toggle="modal"
                            data-target="#bookingModal">
                            <i class="fa fa-plus"></i> Offline Booking
                        </button>
                        @include('back.pages.owner.booking-manage.create')
                        <a href="javascript:void(0);" id="refresh-schedule" type="button" class="btn btn-outline-info"
                            data-toggle="tooltip" title="Cek Jadwal yang Expired"><i
                                class="icon-copy dw dw-wall-clock2"></i> Refresh
                            Jadwal</a>
                    </div> --}}
                    </div>
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus">#</th>
                                <th>Tanggal</th>
                                <th class="truncate">Paket Foto - Venue</th>
                                <th>Harga</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th class="datatable-nosort">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="sortable_services">
                            @if (!$rents->count() > 0)
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-info text-center">Tidak Ada Venue yang Dibooking.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($rents as $rent)
                                    <tr id="rent-{{ $rent->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                                        <td class="truncate">{{ $rent->servicePackageDetail->servicePackage->name }}
                                            ({{ $rent->servicePackageDetail->sum_person }}
                                            Orang)
                                            -
                                            {{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
                                        </td>
                                        <td>Rp{{ number_format($rent->total_price) }}
                                        </td>
                                        <td>
                                            @if ($rent->formatted_schedule == null)
                                                <div class="badge badge-danger">Tidak ada</div>
                                            @else
                                                {{ $rent->formatted_schedule }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if ($rent->formatted_schedule == null)
                                                <span class="badge badge-danger">Jadwal Salah</span>
                                            @else
                                                @if ($rent->rent_status == 0)
                                                    <span class="badge badge-info "><i class="icon-copy dw dw-question"></i>
                                                        Diajukan</span>
                                                @elseif ($rent->rent_status == 1)
                                                    <span class="badge badge-success "><i
                                                            class="icon-copy dw dw-checked"></i>
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
                                                    <span class="badge badge-warning "><i
                                                            class="icon-copy dw dw-money-1"></i>
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
                                        <td style="text-align: center;">
                                            @if ($rent->rent_status != 4)
                                                @if ($rent->dp_payment != null)
                                                    <span class="badge badge-success"><i
                                                            class="icon-copy dw dw-money-2"></i>
                                                        Lunas</span>
                                                @else
                                                    @if ($rent->dp_price == $rent->total_price)
                                                        <span class="badge badge-success"><i
                                                                class="icon-copy dw dw-money-2"></i> Lunas</span>
                                                    @elseif ($rent->dp_price < $rent->total_price && $rent->dp_price != null)
                                                        <span class="badge badge-warning "><i
                                                                class="icon-copy dw dw-money-2"></i>
                                                            Dp (Rp
                                                            {{ number_format($rent->dp_price) }})</span>
                                                    @elseif ($rent->dp_price == null)
                                                        <span class="badge badge-danger "><i
                                                                class="icon-copy dw dw-question"></i> Dp (0)</span>
                                                    @else
                                                        <span class="badge badge-danger "><i
                                                                class="icon-copy dw dw-cancel"></i>
                                                            Tidak Valid</span>
                                                    @endif
                                                @endif
                                            @else
                                                <span class="badge badge-secondary "><i
                                                        class="icon-copy dw dw-calendar-8"></i>
                                                    Expired</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rent->rent_status >= 0 && $rent->rent_status <= 7)
                                                @if ($rent->rent_status == 1)
                                                    {{-- <a href="javascript:void(0);"
                                                        class="btn btn-outline-primary edit-schedule"
                                                        data-rent-id="{{ $rent->id }}" data-toggle="tooltip"
                                                        data-placement="auto" title="Edit Jadwal">
                                                        <i class="dw dw-edit2"></i> Edit
                                                    </a> --}}
                                                @endif
                                                @if ($rent->rent_status == 5)
                                                    <a href="{{ route('customer.booking.show-payment', ['booking' => $rent->id]) }}"
                                                        class="btn btn-warning" data-toggle="tooltip" data-placement="auto"
                                                        title="Pembayaran Awal"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="icon-copy dw dw-money-1"></i> Bayar DP</a>
                                                    <p id="remaining-{{ $rent->id }}"
                                                        style="color:red;text-align:center">
                                                        Waktu Bayar :
                                                    </p>
                                                @endif
                                                @if ($rent->rent_status != 5)
                                                    <a href="{{ route('customer.booking.show', $rent->id) }}"
                                                        class="btn btn-outline-info" data-toggle="tooltip"
                                                        data-placement="auto" title="Detail Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="dw dw-eye"></i> Detail</a>
                                                @endif
                                                {{-- @if ($rent->rent_status == 0)
                                                    <a href="" class="btn btn-success" data-toggle="modal"
                                                        data-target=""
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="icon-copy dw dw-checked"></i> Accept</a>
                                                    <a href="" class="btn btn-danger" data-toggle="modal"
                                                        data-target=""
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="icon-copy dw dw-cancel"></i> Reject</a>
                                                @endif --}}
                                                {{-- @if ($rent->rent_status == 3 || $rent->rent_status == 4 || $rent->rent_status == 5 || $rent->rent_status == 7)
                                                    <a href="javascript:;" class="btn btn-outline-danger deleteBookingBtn"
                                                        data-rent-id="{{ $rent->id }}" data-toggle="tooltip"
                                                        data-rent-name="{{ $rent->name }}" data-placement="auto"
                                                        title="Hapus Booking"
                                                        data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                            class="icon-copy dw dw-delete-3"></i> Delete</a>
                                                @endif --}}
                                            @else
                                                <div class="alert alert-danger">Tidak Valid</div>
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
        {{-- </div> --}}
    </section>
@endsection
@push('scripts')
    {{-- cek expired --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($rents as $rent)
                var rentCreatedAt{{ $rent->id }} = new Date("{{ $rent->created_at }}");
                rentCreatedAt{{ $rent->id }}.setMinutes(rentCreatedAt{{ $rent->id }}.getMinutes() + 30);
                var countDownDate{{ $rent->id }} = rentCreatedAt{{ $rent->id }}.getTime();

                var x{{ $rent->id }} = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate{{ $rent->id }} - now;
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("remaining-{{ $rent->id }}").innerHTML =
                        "Waktu Bayar :  " +
                        minutes + "m " + seconds + "s ";

                    if (distance < 0) {
                        clearInterval(x{{ $rent->id }});
                        document.getElementById("remaining-{{ $rent->id }}").innerHTML =
                            "JADWAL EXPIRED";
                        $.ajax({
                            url: '{{ route('customer.booking.update-status-rent-cust', $rent->id) }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: 4
                            },
                            success: function(response) {
                                console.log(response);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Waktu Pembayaran Habis',
                                    text: 'Waktu Pembayaran Jadwal Tgl {{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }} Jadwal {{ $rent->formatted_schedule }} Sudah Habis. Silahkan lakukan booking ulang jadwal ini.',
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            "{{ route('customer.booking.index') }}";
                                    }
                                });
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                }, 1000);
            @endforeach
        });
    </script>
@endpush
