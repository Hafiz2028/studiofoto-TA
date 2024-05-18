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
                <form action="{{ route('owner.booking.store') }}" method="POST" enctype="multipart/form-data"
                    class="mt-3">
                    <div class="row">
                        <div class="col-lg-5 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label>1. Nama Penyewa</label>
                                <input type="text" class="form-control" value="{{ old('name') }}"
                                    placeholder="Booking jadwal atas nama..." required>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="venue_id">2. Nama Venue</label>
                                <select class="form-control" id="venue_id" onchange="populateServiceTypes()" required>
                                    <option value="" disabled selected>Pilih Venue...</option>
                                    @foreach ($venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label>3. Tipe Layanan</label>
                                <select class="form-control" id="service_type" onchange="populateServiceEvents()"
                                    required>
                                    <option value="" disabled selected>Pilih Tipe Layanan...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>4. Nama Layanan</label>
                                <select class="form-control" id="service_event" onchange="populateServicePackages()"
                                    required>
                                    <option value="" disabled selected>Pilih Layanan...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <div class="form-group">
                                <label>5. Nama Paket</label>
                                <select class="form-control" id="package" onchange="populatePackageDetails()"
                                    required>
                                    <option value="" disabled selected>Pilih Paket Foto...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-6">
                            <div class="form-group">
                                <label>6. Jumlah Orang & Harga</label>
                                <select class="form-control" id="package_detail" onchange="enablePrintPhotoDetails()"
                                    required>
                                    <option value="" disabled selected>Pilih Jumlah Orang...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label>Metode Pembayaran</label>
                            <div class="d-flex justify-content-center align-items-center" id="badge-container">
                                <div class="badge badge-secondary">Paket belum dipilih</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-5 col-sm-6">
                            <label>7. Print Foto & Harga</label>
                            <select class="form-control" id="print_photo_detail" required disabled>
                                <option value="" disabled selected>Pilih Cetak Foto...</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-group">
                                <label>8. Tanggal Booking</label>
                                <input type="text" class="form-control" id="date" required>
                                <input type="hidden" id="opening-hours" value="{{ json_encode($openingHours) }}">
                                <input type="hidden" id="unique-days" value="[]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jadwal Tersedia</label>
                                <div class="card">
                                    <div id="schedule-container" class="card-body"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Harga</label>
                                harga + harga paket foto
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
