<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white" id="bookingModalLabel">
                    <i class="fas fa-calendar-plus mr-2"></i> Tambah Booking Offline
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (auth()->guard('owner')->check())
                    <form action="{{ route('owner.booking.store') }}" method="POST" enctype="multipart/form-data"
                        class="mt-3" id="bookingForm">
                    @elseif(auth()->guard('customer')->check())
                        <form action="{{ route('customer.booking.store') }}" method="POST"
                            enctype="multipart/form-data" class="mt-3" id="bookingForm">
                @endif
                @csrf
                <x-alert.form-alert />
                @if (auth()->guard('owner')->check())
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>1. Nama Penyewa</label>
                                <input type="text" class="form-control" name="name_tenant"
                                    value="{{ old('name_tenant') }}" placeholder="Booking jadwal atas nama..." required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="venue_id">2. Nama Venue</label>
                                <select class="form-control" id="venue_id" name="venue"
                                    onchange="populateServiceTypes()" required>
                                    <option value="" disabled selected>Pilih Venue...</option>
                                    @foreach ($venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>3. Tipe Layanan</label>
                                <select class="form-control" id="service_type" name="service_type"
                                    onchange="populateServiceEvents()" required>
                                    <option value="" disabled selected>Pilih Tipe Layanan...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>4. Nama Layanan</label>
                                <select class="form-control" id="service_event" name="service"
                                    onchange="populateServicePackages()" required>
                                    <option value="" disabled selected>Pilih Layanan...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>5. Nama Paket</label>
                                <select class="form-control" id="package" name="package"
                                    onchange="populatePackageDetails()" required>
                                    <option value="" disabled selected>Pilih Paket Foto...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <input type="hidden" id="hidden_print_photo_detail_id" name="print_photo_detail_id">
                            <div class="form-group">
                                <label>6. Jumlah Orang & Harga</label>
                                <select class="form-control" id="package_detail" name="package_detail"
                                    onchange="enablePrintPhotoDetails()" required>
                                    <option value="" disabled selected>Pilih Jumlah Orang...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="d-flex flex-column justify-content-center align-items-center mt-3"
                                id="badge-container">
                                <div class="badge badge-secondary mb-1">Waktu Pemotretan</div>
                                <div class="badge badge-secondary">DP / Hanya Lunas</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <label>7. Print Foto & Harga</label>
                            <select class="form-control" id="print_photo_detail" name="print_photo_detail" required
                                disabled>
                                <option value="" disabled selected>Pilih Cetak Foto...</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>8. Tanggal Booking</label>
                                <input type="text" class="form-control" id="date" name="date" required
                                    disabled>
                                <small class="text-muted">Format: DD/MM/YYYY</small> <input type="hidden"
                                    id="opening-hours" value="{{ json_encode($openingHours) }}">
                                <input type="hidden" id="unique-days" value="{{ json_encode($uniqueDayIds) }}">
                                <input type="hidden" id="book-dates" value="{{ json_encode($bookDates) }}">
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12">
                            <div class="form-group">
                                <label>9. Jadwal Tersedia</label>
                                <div class="card">
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
                        <div class="col-lg-3 col-md-12 mt-4">
                            <div class="form-group d-flex align-items-center">
                                <label>Paket Foto : </label>
                                <span id="package-price" class="badge badge-primary ml-2">Rp 0</span>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <label>Cetak Foto : </label>
                                <span id="print-photo-price" class="badge badge-info ml-2">Rp 0</span>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <label>Total Harga : </label>
                                <span id="total-price" class="badge badge-success ml-2">Rp 0</span>
                            </div>
                        </div>
                    </div>
                @elseif (auth()->guard('customer')->check())
                @endif
                <input type="hidden" name="total_price" id="total-price-input" value="0">
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Pembayaran</button>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>
