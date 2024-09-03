<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                @if (auth()->check() && auth()->user()->role === 'owner')
                    <h5 class="modal-title text-white" id="bookingModalLabel">
                        <i class="fas fa-calendar-plus mr-2"></i> Tambah Booking Offline
                    </h5>
                @elseif (auth()->check() && auth()->user()->role === 'customer')
                    <h5 class="modal-title text-white" id="bookingModalLabel">
                        <i class="fas fa-calendar-plus mr-2"></i> Tambah Booking <b>{{ $venue->name }}</b>
                    </h5>
                @endif
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (auth()->check() && auth()->user()->role === 'owner')
                    <form action="{{ route('owner.booking.store') }}" method="POST" enctype="multipart/form-data"
                        class="mt-3" id="bookingForm">
                        @csrf
                        <x-alert.form-alert />
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>1. Nama Penyewa</label>
                                    <input type="text" class="form-control" name="name_tenant"
                                        value="{{ old('name_tenant') }}" placeholder="Booking jadwal atas nama..."
                                        required>
                                    <small>Contoh : asep, juki...</small>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>2. No HP / WA</label>
                                    <input type="number" class="form-control" name="no_hp"
                                        value="{{ old('no_hp') }}" placeholder="Nomor yang bisa dihubungi..." required>
                                    <small>Format no HP: 628xxxxxxxx</small>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="venue_id">3. Nama Venue</label>
                                    <select class="custom-select2 form-control" id="venue_id" name="venue"
                                        onchange="populateServicesAndEvents()" style="width: 100%; height: 38px"
                                        required>
                                        <option value="" disabled selected>Pilih Venue...</option>
                                        @foreach ($venues as $venue)
                                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label>4. Nama Layanan</label>
                                    <select class="custom-select2 form-control" name="service" id="service"
                                        style="width: 100%; height: 38px" onchange="populatePackageAndDetails()"
                                        required>
                                        <option value="" disabled selected>Pilih Layanan...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>5. Paket Foto</label>
                                    <select class="custom-select2 form-control" style="width: 100%; height: 38px"
                                        id="package_detail" name="package_detail"
                                        onchange="updatePaymentMethodBadge(); toggleDateInput();" required>
                                        <option value="" disabled selected>Pilih Paket Foto...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="d-flex flex-column justify-content-center align-items-center mt-3"
                                    id="badge-container">
                                    <div class="badge badge-secondary mb-1">Waktu Pemotretan</div>
                                    <div class="badge badge-secondary">DP / Hanya Lunas</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>6. Tanggal Booking</label>
                                    <input type="text" class="form-control" id="date" name="date" required
                                        disabled>
                                    <small class="text-muted">Format: DD/MM/YYYY</small> <input type="hidden"
                                        id="opening-hours" value="{{ json_encode($openingHours) }}">
                                    <input type="hidden" id="unique-days" value="{{ json_encode($uniqueDayIds) }}">
                                    <input type="hidden" id="book-dates" value="{{ json_encode($bookDates) }}">
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="form-group">
                                    <label>7. Jadwal Tersedia</label>
                                    <div class="card shadow">
                                        <div class="card-header d-flex justify-content-end">
                                            <div class="badge badge-primary">Tersedia</div>
                                            <div class="badge badge-secondary ml-1">Tutup</div>
                                            <div class="badge badge-danger ml-1">Telah dibooking</div>
                                            <div class="badge badge-success ml-1">Dipilih</div>
                                        </div>
                                        <div id="schedule-container" class="card-body">
                                            <div class="alert alert-info">Belum memilih tanggal dan venue</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="card shadow">
                                    <div class="card-header d-flex justify-content-center">
                                        <h6 class="text-center">Detail Paket</h6>
                                    </div>
                                    <div class="card body p-3">
                                        <div id="detail-container">
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Harga Paket</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i class="icon-copy dw dw-money"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Include AddOn</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i
                                                        class="icon-copy dw dw-photo-camera1"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Include Cetak
                                                    Foto</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i class="icon-copy dw dw-print"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Include Frame
                                                    Foto</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i class="icon-copy dw dw-image1"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                        </div>
                                        <div id="detail-schedule-container">
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Jadwal
                                                    Booking</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i
                                                        class="icon-copy dw dw-wall-clock2"></i>
                                                    Belum Pilih Jadwal</div>
                                                </p>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="total_price" id="total-price-input" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Pembayaran</button>
                        </div>
                    </form>
                @elseif (auth()->check() && auth()->user()->role === 'customer')
                    <form action="{{ route('customer.booking.store') }}" method="POST" enctype="multipart/form-data"
                        class="mt-3" id="bookingForm">
                        @csrf
                        <x-alert.form-alert />
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="venue_id">1. Nama Venue</label>
                                    <input class="form-control" type="text" name="venue"
                                        value="{{ $venue->name }}" readonly>
                                    <input class="form-control" type="text" name="venue" id="venue_id"
                                        value="{{ $venue->id }}" hidden>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>2. Nama Penyewa</label>
                                    <input type="text" class="form-control" name="name_tenant"
                                        value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>3. No HP / WA</label>
                                    <input type="number" class="form-control" name="no_hp"
                                        value="{{ old('no_hp') }}" placeholder="Nomor yang bisa dihubungi..."
                                        required>
                                    <small class="ml-1">Format no HP: 628xxxxxxxx</small>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>4. Nama Layanan</label>
                                    <select class="custom-select2 form-control" id="service" name="service"
                                        style="width: 100%; height: 38px" onchange="populatePackageAndDetails()"
                                        required>
                                        <option value="" disabled selected>Pilih Layanan...</option>
                                        @foreach ($venue->serviceEvents as $serviceEvent)
                                            <optgroup label="{{ $serviceEvent->serviceType->service_name }}">
                                                <option value="{{ $serviceEvent->id }}">{{ $serviceEvent->name }}
                                                </option>
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>5. Paket Foto</label>
                                    <select class="custom-select2 form-control" style="width: 100%; height: 38px"
                                        id="package_detail" name="package_detail"
                                        onchange="updatePaymentMethodBadge(); toggleDateInput();" required>
                                        <option value="" disabled selected>Pilih Paket Foto...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="d-flex flex-column justify-content-center align-items-center mt-3"
                                    id="badge-container">
                                    <div class="badge badge-secondary mb-1">Waktu Pemotretan</div>
                                    <div class="badge badge-secondary">DP / Hanya Lunas</div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>6. Tanggal Booking</label>
                                    <input type="text" class="form-control" id="date" name="date"
                                        required disabled>
                                    <small class="text-muted">Format: DD/MM/YYYY</small> <input type="hidden"
                                        id="opening-hours" value="{{ json_encode($openingHours) }}">
                                    <input type="hidden" id="unique-days" value="{{ json_encode($uniqueDayIds) }}">
                                    <input type="hidden" id="book-dates" value="{{ json_encode($bookDates) }}">
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="form-group">
                                    <label>7. Jadwal Tersedia</label>
                                    <div class="card shadow">
                                        <div class="card-header d-flex justify-content-end">
                                            <div class="badge badge-primary">Tersedia</div>
                                            <div class="badge badge-secondary ml-1">Tutup</div>
                                            <div class="badge badge-danger ml-1">Telah dibooking</div>
                                            <div class="badge badge-success ml-1">Dipilih</div>
                                        </div>
                                        <div id="schedule-container" class="card-body">
                                            <div class="alert alert-info">Belum memilih tanggal dan venue</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-warning text-center">
                                    <i class="icon-copy fa fa-info-circle" aria-hidden="true"></i> Pastikan melakukan
                                    konfirmasi ulang
                                    booking kepada pihak owner maksimal 30 menit sebelum jadwal dimulai. <br>Booking
                                    akan dibatalkan secara sepihak jika tidak
                                    melakukan konfirmasi dan tidak ada kabar sebelum jadwal booking dimulai.
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="card shadow">
                                    <div class="card-header d-flex justify-content-center">
                                        <h6 class="text-center">Detail Paket</h6>
                                    </div>
                                    <div class="card body p-3">
                                        <div id="detail-container">
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Harga Paket</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i class="icon-copy dw dw-money"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Include AddOn</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i
                                                        class="icon-copy dw dw-photo-camera1"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Include Cetak
                                                    Foto</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i class="icon-copy dw dw-print"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Include Frame
                                                    Foto</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i class="icon-copy dw dw-image1"></i>
                                                    Belum pilih paket foto</div>
                                                </p>
                                            </h6>
                                        </div>
                                        <div id="detail-schedule-container">
                                            <h6 class="mb-2">
                                                <span style="display: inline-block; width: 160px;">Jadwal
                                                    Booking</span>
                                                : <p style="display: inline-block; margin: 0; font-weight: normal;">
                                                <div class="badge badge-info"><i
                                                        class="icon-copy dw dw-wall-clock2"></i>
                                                    Belum Pilih Jadwal</div>
                                                </p>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="total_price" id="total-price-input" value="0">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Pembayaran</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
