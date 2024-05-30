<div class="modal fade" id="editBookingModal{{ $rent->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editBookingModalLabel{{ $rent->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white" id="editBookingModalLabel{{ $rent->id }}">
                    <i class="fas fa-calendar-plus mr-2"></i> Edit Booking
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('owner.booking.update', $rent->id) }}" method="POST"
                    enctype="multipart/form-data" class="mt-3" id="editBookingForm{{ $rent->id }}">
                    @csrf
                    @method('PUT')
                    <x-alert.form-alert />
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>1. Nama Penyewa</label>
                                <input type="text" class="form-control" name="name_tenant"
                                    value="{{ $rent->name }}" placeholder="Booking jadwal atas nama..." required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="venue_id">2. Nama Venue</label>
                                <select class="form-control venue-id" id="venue_id" name="venue" required>
                                    <option value="" disabled>Pilih Venue...</option>
                                    @foreach ($venues as $venue)
                                        <option value="{{ $venue->id }}"
                                            @if ($venue->id == $rent->venue_id) selected @endif>{{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>3. Tipe Layanan</label>
                                <select class="form-control service-type" id="service_type" name="service_type"
                                    required>
                                    <option value="" disabled>Pilih Tipe Layanan...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>4. Nama Layanan</label>
                                <select class="form-control service-event" id="service_event" name="service" required>
                                    <option value="" disabled>Pilih Layanan...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>5. Nama Paket</label>
                                <select class="form-control package" id="package" name="package" required>
                                    <option value="" disabled>Pilih Paket Foto...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <input type="hidden" id="hidden_print_photo_detail_id" name="print_photo_detail_id"
                                value="{{ $rent->print_photo_detail_id }}">
                            <div class="form-group">
                                <label>6. Jumlah Orang & Harga</label>
                                <select class="form-control package-detail" id="package_detail" name="package_detail"
                                    required>
                                    <option value="" disabled>Pilih Jumlah Orang...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="d-flex flex-column justify-content-center align-items-center mt-3"
                                id="badge-container">
                                @if ($rent->servicePackageDetail->time_status == 0)
                                    <div class="badge badge-success mb-1 time-badge">30 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 1)
                                    <div class="badge badge-primary mb-1 time-badge">60 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 2)
                                    <div class="badge badge-info mb-1 time-badge">90 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 3)
                                    <div class="badge badge-warning mb-1 time-badge">120 Menit</div>
                                @else
                                    <div class="badge badge-danger mb-1 time-badge">Tidak Valid</div>
                                @endif

                                @if ($rent->servicePackageDetail->servicePackage->dp_status == 0)
                                    <div class="badge badge-info dp-badge">Hanya Lunas</div>
                                @elseif ($rent->servicePackageDetail->servicePackage->dp_status == 1)
                                    <div class="badge badge-success dp-badge">DP Minimal
                                        {{ $rent->servicePackageDetail->servicePackage->dp_percentage * 100 }}%</div>
                                @elseif ($rent->servicePackageDetail->servicePackage->dp_status == 2)
                                    <div class="badge badge-success dp-badge">Min. Bayar Rp
                                        {{ number_format($rent->servicePackageDetail->servicePackage->dp_min) }}</div>
                                @else
                                    <div class="badge badge-danger dp-badge">Tidak Valid</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <label>7. Print Foto & Harga</label>
                            <select class="form-control print-photo-detail" id="print_photo_detail"
                                name="print_photo_detail" required>
                                <option value="" disabled>Pilih Cetak Foto...</option>
                                @if (is_null($rent->print_photo_detail_id))
                                    <option value="" selected>Tidak ada Cetak Foto</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>8. Tanggal Booking</label>
                                <input type="text" class="form-control date" id="date" name="date"
                                    value="{{ $rent->date }}" required>
                                <input type="hidden" id="opening-hours" value="{{ json_encode($openingHours) }}">
                                <input type="hidden" id="unique-days" value="{{ json_encode($uniqueDayIds) }}">
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-12">
                            <div class="form-group">
                                <label>9. Jadwal Tersedia</label>
                                <div class="card">
                                    <div class="card-header d-flex justify-content-end">
                                        <div class="badge badge-primary">Tersedia</div>
                                        <div class="badge badge-secondary ml-1">Tutup</div>
                                        <div class="badge badge-danger ml-1">Telah dibooking</div>
                                        <div class="badge badge-success ml-1">Dipilih</div>
                                    </div>
                                    <div class="schedule-container" id="schedule-container-{{ $rent->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="form-group d-flex align-items-center">
                                <label for="package-price">Harga Paket Foto</label>
                                <span id="package-price" class="badge badge-primary ml-2">Rp
                                    {{ number_format($rent->servicePackageDetail->price) }}</span>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <label for="print-photo-price">Harga Cetak Foto</label>
                                <span id="print-photo-price" class="badge badge-info ml-2">Rp
                                    {{ $rent->printPhotoDetail ? number_format($rent->printPhotoDetail->printServiceEvent->price) : number_format(0) }}
                                </span>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <label for="total-price">Harga Total</label>
                                <span id="total-price" class="badge badge-success ml-2">Rp
                                    {{ number_format($rent->total_price) }}</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="total_price" id="total-price-input"
                        value="{{ $rent->total_price }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
