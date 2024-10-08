@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Owner Home')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="pull-left">
                    <div class="title">
                        <h4>Welcome, {{ ucwords(strtolower($user->name)) }} </h4>
                    </div>
                </div>
                <div class="pull-right">
                    <a href="javascript:void(0);" id="refresh-schedule" type="button" class="btn btn-info"
                        data-toggle="tooltip" title="Cek Jadwal yang Expired"><i class="icon-copy dw dw-wall-clock2"></i>
                        Refresh
                        Jadwal</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var ownerID = "{{ $owner->id }}";
            var today = new Date();
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: today,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '/api/rent-events/' + ownerID,
                        dataType: 'json',
                        success: function(response) {
                            console.log('Response:', response);
                            var events = [];
                            response.forEach(function(rent) {
                                if (rent.rent_details) {
                                    var timeStatus = rent
                                        .service_package_detail
                                        .time_status;
                                    var rentDetailsLength = rent.rent_details
                                        .length;
                                    var startTime = rent.date + 'T' +
                                        rent.rent_details[0]
                                        .opening_hour.hour
                                        .hour.replace('.', ':');
                                    var endTime = new Date(startTime);
                                    endTime.setMinutes(endTime.getMinutes() +
                                        (timeStatus * 30) + 30
                                    );
                                    var colorClass =
                                        'bg-info text-white';
                                    switch (rent.rent_status) {
                                        case 0:
                                            colorClass =
                                                'bg-info text-white';
                                            break;
                                        case 1:
                                            colorClass =
                                                'bg-success text-white';
                                            break;
                                        case 2:
                                            colorClass =
                                                'bg-primary text-white';
                                            break;
                                        case 3:
                                            colorClass =
                                                'bg-danger text-white';
                                            break;
                                        case 4:
                                            colorClass =
                                                'bg-secondary text-white';
                                            break;
                                        case 5:
                                            colorClass =
                                                'bg-warning';
                                            break;
                                        case 6:
                                            colorClass =
                                                'bg-dark text-white';
                                            break;
                                    }
                                    events.push({
                                        title: rent.name + ' - ' + rent
                                            .service_package_detail
                                            .service_package
                                            .service_event.venue
                                            .name,
                                        start: startTime,
                                        end: endTime,
                                        classNames: [
                                            colorClass
                                        ],
                                        url: '/owner/booking/' +
                                            rent.id
                                    });
                                } else {
                                    console.log('data kosong');
                                }
                            });
                            successCallback(events);
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                }
            });
            calendar.render();
        });
    </script>
    <div class="row p-0">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-secondary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $rents->count() }}</div>
                        <div class="font-14 text-white weight-500">
                            Total Booking
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-secondary">
                            <i class="icon-copy dw dw-calendar1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-info">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $rents->where('rent_status', 0)->count() }}
                        </div>
                        <div class="font-14 text-white weight-500">
                            Booking Diajukan
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-info">
                            <i class="icon-copy dw dw-question"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-success">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $rents->where('rent_status', 1)->count() }}
                        </div>
                        <div class="font-14 text-white weight-500">
                            Berhasil Dibooking
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-success">
                            <i class="icon-copy dw dw-checked"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $rents->where('rent_status', 2)->count() }}
                        </div>
                        <div class="font-14 text-white weight-500">
                            Booking Selesai
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-primary">
                            <i class="icon-copy fa fa-calendar-check-o" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-0 ">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-primary">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $venues->count() }}</div>
                        <div class="font-14 weight-500 text-white">
                            Total Venue
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-primary">
                            <i class="icon-copy dw dw-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-success">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $venues->where('status', 1)->count() }}</div>
                        <div class="font-14 text-white weight-500">
                            Venue Aktif
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-success">
                            <i class="icon-copy dw dw-checked"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-20">
            <div class="card-box height-100-p widget-style3 bg-info">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-white">{{ $venues->where('status', 0)->count() }}</div>
                        <div class="font-14 text-white weight-500">
                            Venue Belum Dikonfirmasi
                        </div>
                    </div>
                    <div class="widget-icon bg-white">
                        <div class="icon text-info">
                            <i class="icon-copy dw dw-question"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-0">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="pd-10 card-box mb-30">
                <div class="clearfix mb-4">
                    <div class="pull-right">
                        <div class="badge-container">
                            <span class="badge badge-info" data-toggle="tooltip" data-placement="auto"
                                title="Jadwal Booking sedang diajukan">Diajukan</span>
                            <span class="badge badge-success" data-toggle="tooltip" data-placement="auto"
                                title="Jadwal berhasil dibooking">Dibooking</span>
                            <span class="badge badge-primary" data-toggle="tooltip" data-placement="auto"
                                title="Pemotretan telah selesai">Selesai</span>
                            <span class="badge badge-danger" data-toggle="tooltip" data-placement="auto"
                                title="Jadwal Booking ditolak">Ditolak</span>
                            <span class="badge badge-secondary" data-toggle="tooltip" data-placement="auto"
                                title="Jadwal Booking telah expired">Expired</span>
                            <span class="badge badge-warning" data-toggle="tooltip" data-placement="auto"
                                title="Belum membayar DP">Belum Bayar</span>
                            <span class="badge badge-dark" data-toggle="tooltip" data-placement="auto"
                                title="Sedang dalam proses pemotretan">Sedang Foto</span>
                            <span class="badge badge-danger" data-toggle="tooltip" data-placement="auto"
                                title="Jadwal Booking Dibatalkan">Dibatalkan</span>
                        </div>
                    </div>
                </div>
                <div id='calendar'></div>
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    {{-- cek jadwal expired --}}
    <script>
        document.getElementById('refresh-schedule').addEventListener('click', function() {
            $.ajax({
                url: '{{ route('owner.booking.update-status') }}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status === 'success') {
                        console.log(data.message);
                        console.log(data.openingHour);
                        console.log(data.scheduleTime);
                        console.log(data.cutoffTime);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Jadwal Expired',
                            html: data.message.join('<br>')
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        console.log(data.message);
                        console.log(data.openingHour);
                        console.log(data.scheduleTime);
                        console.log(data.cutoffTime);
                        Swal.fire({
                            icon: 'success',
                            title: 'Jadwal Expired',
                            text: data.message
                        });
                    }
                },
                error: function() {
                    console.log('Something went wrong!');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                }
            });
        });
    </script>
@endpush
