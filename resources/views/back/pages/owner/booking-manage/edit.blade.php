<div class="modal fade" id="editBookingModal{{ $rent->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white" id="editBookingModalLabel">
                    <i class="fas fa-calendar-plus mr-2"></i> Edit Booking
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('owner.booking.update', $rent->id) }}" method="POST"
                    enctype="multipart/form-data" class="mt-3" id="editBookingForm{{ $rent->id }}">
                    <input type="hidden" name="selected_rent" id="selected_rent">
                    @csrf
                    @method('PUT')
                    <x-alert.form-alert />
                    <?php echo $rent->id; ?>
                    <?php echo $rent->name; ?>
                    <?php echo $rent->date; ?>
                    <?php echo $rent->servicePackageDetail->servicePackage->serviceEvent->venue_id; ?>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>1. Nama Penyewa</label>
                                <input type="text" class="form-control" name="name_tenant"
                                    value="{{ $rent->name }}" placeholder="Booking jadwal atas nama..." disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="edit-venue_id">2. Nama Venue</label>
                                <input type="text" class="form-control venue-id" id="edit-venue_name"
                                    name="venue_name"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue->name }} {{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue_id }}"
                                    disabled>
                                <input type="hidden" id="edit-selected-venue-id"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->venue_id }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>3. Tipe Layanan</label>
                                <input class="form-control service-type" id="service_type" name="service_type"
                                    type="text"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->serviceType->service_name }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>4. Nama Layanan</label>
                                <input class="form-control service-event" id="service_event" name="service"
                                    type="text"
                                    value="{{ $rent->servicePackageDetail->servicePackage->serviceEvent->name }}"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>5. Nama Paket</label>
                                @php
                                    $servicePackageName = $rent->servicePackageDetail->servicePackage->name;
                                    $addOnDetails = '';
                                    if (
                                        $rent->servicePackageDetail->servicePackage->addOnPackageDetails->isNotEmpty()
                                    ) {
                                        $addOnDetailsArray = [];
                                        foreach (
                                            $rent->servicePackageDetail->servicePackage->addOnPackageDetails
                                            as $addOnPackageDetail
                                        ) {
                                            $addOnDetailsArray[] =
                                                '+ (' .
                                                $addOnPackageDetail->sum .
                                                ' ' .
                                                $addOnPackageDetail->addOnPackage->name .
                                                ')';
                                        }
                                        $addOnDetails = implode(' ', $addOnDetailsArray);
                                    }
                                    $inputValue = trim($servicePackageName . ' ' . $addOnDetails);
                                @endphp
                                <input class="form-control package" id="package" name="package" type="text"
                                    value="{{ $inputValue }}" disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>6. Jumlah Orang & Harga</label>
                                <input class="form-control package-detail" id="edit-package-detail"
                                    name="package_detail" data-edit-package-id="{{ $rent->service_package_detail_id }}"
                                    data-time-status="{{ $rent->servicePackageDetail->time_status }}" type="text"
                                    value="{{ $rent->servicePackageDetail->sum_person }} Orang - Rp {{ number_format($rent->servicePackageDetail->price, 0, 0) }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="d-flex flex-column justify-content-center align-items-center mt-3"
                                id="badge-container">
                                @if ($rent->servicePackageDetail->time_status == 0)
                                    <div class="badge badge-success mb-1 time-badge" data-time-status="0">30 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 1)
                                    <div class="badge badge-primary mb-1 time-badge" data-time-status="1">60 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 2)
                                    <div class="badge badge-info mb-1 time-badge" data-time-status="2">90 Menit</div>
                                @elseif ($rent->servicePackageDetail->time_status == 3)
                                    <div class="badge badge-warning mb-1 time-badge" data-time-status="3">120 Menit
                                    </div>
                                @else
                                    <div class="badge badge-danger mb-1 time-badge" data-time-status="-1">Tidak Valid
                                    </div>
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
                            <input type="hidden" id="hidden_print_photo_detail_id" name="print_photo_detail_id"
                                value="{{ $rent->print_photo_detail_id }}">
                            <input class="form-control print-photo-detail" id="print_photo_detail"
                                name="print_photo_detail"
                                value="{{ is_null($rent->print_photo_detail_id) ? 'Tidak ada Cetak Foto' : 'Size ' . $rent->printPhotoDetail->printServiceEvent->printPhoto->size . ' - Harga Rp ' . number_format($rent->printPhotoDetail->printServiceEvent->price) }}"
                                disabled>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>8. Tanggal Booking</label>
                                <input type="text" class="form-control date" id="edit-date" name="date"
                                    value="{{ \Carbon\Carbon::parse($rent->date)->format('d/m/Y') }}"
                                    pattern="\d{2}/\d{2}/\d{4}" required>
                                <small class="text-muted">Format: DD/MM/YYYY</small>
                                <input type="hidden" id="edit-opening-hours"
                                    value="{{ json_encode($openingHours) }}">
                                <input type="hidden" id="edit-unique-days"
                                    value="{{ json_encode($uniqueDayIds) }}">
                                <input type="hidden" id="book-dates" value="{{ json_encode($bookDates) }}">
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
                                    <div id="edit-schedule-container" class="card-body">
                                        <div class="alert alert-info">Belum memilih tanggal dan venue</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="opening_hours[]" id="opening-hours-input">
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
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
