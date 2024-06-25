@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'History')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>History List</h4>
                </div>
                <nav aria-label="breadcrumb " role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            History List
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix mb-3">
                        <x-alert.form-alert />
                        <div class="pull-left">
                            <h4 class="h4 text">History Booking Studio Owner <span
                                    class="text-primary">{{ ucfirst(Auth::user()->name) }}</span></h4>
                        </div>
                        <div class="pull-right">
                        </div>
                    </div>
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus">#</th>
                                <th>Tanggal</th>
                                <th>Nama Tenant</th>
                                <th>Venue</th>
                                <th>Jadwal Foto</th>
                                <th>Status</th>
                                <th class="datatable-nosort">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="sortable_services">
                            @if (!$rents->count() > 0)
                                <tr>
                                    <td colspan="10">
                                        <div class="alert alert-info text-center">Tidak Ada History Booking.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($rents as $rent)
                                    <tr id="rent-{{ $rent->id }}">
                                        <td class="table-plus">{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rent->date)->format('d M Y') }}</td>
                                        <td>{{ $rent->name }}
                                        </td>
                                        <td>{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }}
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
                                                            class="icon-copy dw dw-checked"></i> Dibooking</span>
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
                                                            class="icon-copy dw dw-money-1"></i> Belum Bayar</span>
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
                                        <td>
                                            <a href="{{ route('owner.booking.show', $rent->id) }}"
                                                class="btn btn-info" data-toggle="tooltip" data-placement="auto"
                                                title="Detail Booking"
                                                data-schedule="{{ $rent->formatted_schedule ?? 'null' }}"><i
                                                    class="dw dw-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
