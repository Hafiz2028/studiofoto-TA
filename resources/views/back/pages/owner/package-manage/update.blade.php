@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Package')
@section('content')

    <div class="page-header">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-dark">Edit Package</h4>
            </div>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.venue.index') }}">Venue's Manage</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.venue.show', $venue->id) }}">Detail Venue</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.venue.services.show', ['venue' => $venue->id, 'service' => $service->id]) }}">Detail
                        Service</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Edit Package
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-primary">Edit Package</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('owner.venue.services.show', ['venue' => $venue->id, 'service' => $service->id]) }}"
                            class="btn btn-outline-info btn-sm">
                            <i class="ion-arrow-left-a"></i> Kembali
                        </a>
                    </div>
                </div>
                <hr>


                <form
                    action="{{ route('owner.venue.services.packages.update', ['venue' => $venue->id, 'service' => $service->id, 'package' => $package->id]) }}"
                    method="POST" enctype="multipart/form-data" class="mt-3">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <x-alert.form-alert />

                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Nama Layanan</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Contoh: Wisuda 1, Diamond 1"
                                        value="{{ $package->name }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="information">Deskripsi Paket Foto</label>
                                    <textarea class="form-control @error('information') is-invalid @enderror" id="information" name="information"
                                        rows="4"
                                        placeholder="Contoh : Paket ini memiliki berbagai macam tambahan foto dan cetak foto dengan berbagai ukuran..."
                                        style="height: 130px;">{{ $package->information }}</textarea>
                                    @error('information')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Maksimal Waktu Pemotretan</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="time_30" name="time_status"
                                            value="0" {{ $package->time_status == 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="time_30">30 Menit</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="time_60" name="time_status"
                                            value="1" {{ $package->time_status == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="time_60">60 Menit</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="time_90" name="time_status"
                                            value="2" {{ $package->time_status == 2 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="time_90">90 Menit</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="time_120" name="time_status"
                                            value="3" {{ $package->time_status == 3 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="time_120">120 Menit</label>
                                    </div>
                                    @error('time_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label for="add_on_switch">Add On</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="add_on_switch"
                                            name="add_on_switch" {{ $package->addOnPackageDetails->isNotEmpty() ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="add_on_switch">Aktifkan Tambahan</label>
                                    </div>
                                </div>
                                <div id="add_on_options" style="{{ $package->addOnPackageDetails->isNotEmpty() ? '' : 'display: none;' }}">
                                    <label for="add_ons">Tambahkan Total Add On untuk paket ini </label><br>
                                    @foreach ($addOnPackages as $addOnPackage)
                                        <div class="addon-item custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input addon-checkbox"
                                                id="add_on_{{ $addOnPackage->id }}" name="add_ons[]"
                                                value="{{ $addOnPackage->id }}" data-id="{{ $addOnPackage->id }}"
                                                {{ $package->addOnPackageDetails->contains('add_on_package_id', $addOnPackage->id) ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="add_on_{{ $addOnPackage->id }}">{{ $addOnPackage->name }}</label>
                                            <div class="addon-inputs" style="{{ $package->addOnPackageDetails->contains('add_on_package_id', $addOnPackage->id) ? '' : 'display: none;' }}">
                                                <div class="addon-controls">
                                                    <input type="number" id="total_qty_{{ $addOnPackage->id }}"
                                                        name="total_qty_{{ $addOnPackage->id }}"
                                                        class="form-control addon-qty"
                                                        placeholder="Jumlah {{ $addOnPackage->name }} (qty)"
                                                        value="{{ $getQtyByAddOnPackageId($addOnPackage->id) }}"
                                                        onchange="updateSum('{{ $addOnPackage->id }}')">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('add_ons')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dp_percentage">Metode Pembayaran Tambahan</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="full_payment_option" name="dp_percentage"
                                            class="custom-control-input" value="full_payment"
                                            {{ $package->dp_status == 0 ? 'checked' : '' }}>
                                        <!-- Tambahkan atribut checked di sini -->
                                        <label class="custom-control-label" for="full_payment_option">Hanya Pembayaran
                                            Lunas</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="dp_option" name="dp_percentage"
                                            class="custom-control-input" value="dp"
                                            {{ $package->dp_status == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="dp_option">DP %</label>
                                        <input type="number" id="dp_input" name="dp_input" class="form-control"
                                            min="1" max="100"
                                            {{ $package->dp_status == 1 ? '' : 'style=display:none' }}
                                            value="{{ $package->dp_status == 1 ? $package->dp_percentage * 100 : '' }}">
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="min_payment_option" name="dp_percentage"
                                            class="custom-control-input" value="min_payment"
                                            {{ $package->dp_status == 2 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="min_payment_option">Minimal
                                            Pembayaran</label>
                                        <div id="min_payment_input_group"
                                            style="{{ $package->dp_status == 2 ? '' : 'display: none;' }}">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" id="min_payment_input" name="min_payment_input"
                                                    class="form-control"
                                                    value="{{ $package->dp_status == 2 ? $package->dp_percentage * $package->price : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @error('dp_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Harga Paket</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" id="package_price" name="price"
                                            class="form-control @error('price') is-invalid @enderror"
                                            placeholder="Tambahkan Harga Paket..." value="{{ $package->price}}" required>
                                    </div>
                                    <p class="alert alert-info">Harga Cetak Foto Terpisah</p>
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="print_photos_switch">Cetak Foto</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="print_photos_switch"
                                            name="print_photos_switch" {{ $package->printPhotoDetails->isNotEmpty() ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="print_photos_switch">Aktifkan Cetak
                                            Foto</label>
                                    </div>
                                </div>
                                <div id="print_photos_options" style="{{ $package->printPhotoDetails->isNotEmpty() ? '' : 'display: none;' }}">
                                    <label for="print_photos">Pilih Ukuran Cetak Paket Foto:</label><br>
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <button id="check-all-button" type="button"
                                                class="btn btn-outline-success mr-2" data-toggle="check-all"
                                                title="Ceklis semua ukuran foto"><i class="bi bi-check-all"></i></button>
                                            <button id="uncheck-all-button" type="button" class="btn btn-outline-danger"
                                                data-toggle="uncheck-all" title="Uncheck semua ukuran foto"><i
                                                    class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @php
                                            $columnCount = 3;
                                            $rowCount = ceil(count($printServiceEvents) / $columnCount);
                                        @endphp
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            <div class="col-md-{{ 12 / $columnCount }}">
                                                @for ($j = $i * $columnCount; $j < min(($i + 1) * $columnCount, count($printServiceEvents)); $j++)
                                                    @php $printServiceEvent = $printServiceEvents[$j]; @endphp
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="print_photo_{{ $printServiceEvent->id }}"
                                                            name="print_photos[]" value="{{ $printServiceEvent->id }}"
                                                            data-price="{{ $printServiceEvent->price }}" {{ $package->printPhotoDetails->contains('print_service_event_id', $printServiceEvent->id) ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="print_photo_{{ $printServiceEvent->id }}">{{ $printServiceEvent->printPhoto->size }}
                                                            (Harga Rp
                                                            <strong>{{ $printServiceEvent->price ? number_format($printServiceEvent->price, 0, ',', '.') : '0' }}</strong>)</label>
                                                    </div>
                                                @endfor
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Perkiraan Harga</label><br>
                                    <p id="estimated_price" class="alert alert-secondary"
                                        style="display: inline-block; width: fit-content;">Rp {{ $package->price ?: '0' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary float-right">Update Package</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('styles')
    <style>
        .addon-controls {
            display: flex;
            align-items: center;
        }

        .addon-controls button {
            margin-left: 5px;
        }
    </style>
@endpush
@push('scripts')
    {{-- metode pembayaran  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dpOption = document.getElementById('dp_option');
            const minPaymentOption = document.getElementById('min_payment_option');
            const dpInput = document.getElementById('dp_input');
            const minPaymentInputGroup = document.getElementById('min_payment_input_group');
            const priceInput = document.getElementById('price');

            togglePaymentInputs();

            dpOption.addEventListener('change', togglePaymentInputs);
            minPaymentOption.addEventListener('change', togglePaymentInputs);

            const fullPaymentOption = document.getElementById('full_payment_option');
            fullPaymentOption.addEventListener('change', function() {
                dpInput.style.display = 'none';
                minPaymentInputGroup.style.display = 'none';
                priceInput.value = '';
            });

            function togglePaymentInputs() {
                if (dpOption.checked) {
                    dpInput.style.display = 'block';
                    minPaymentInputGroup.style.display = 'none';
                } else if (minPaymentOption.checked) {
                    dpInput.style.display = 'none';
                    minPaymentInputGroup.style.display = 'block';
                } else {
                    dpInput.style.display = 'none';
                    minPaymentInputGroup.style.display = 'none';
                }
            }

            if (!dpOption.checked) {
                dpInput.value = '';
            }
            if (!minPaymentOption.checked) {
                priceInput.value = '';
            }
        });
    </script>
    {{-- print foto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printServiceEventsSwitch = document.getElementById('print_photos_switch');
            const printServiceEventsOptions = document.getElementById('print_photos_options');
            const checkAllButton = document.querySelector('[data-toggle="check-all"]');
            const uncheckAllButton = document.querySelector('[data-toggle="uncheck-all"]');
            const printServiceEventCheckboxes = document.querySelectorAll('input[name="print_photos[]"]');

            printServiceEventsSwitch.addEventListener('change', function() {
                if (this.checked) {
                    printServiceEventsOptions.style.display = 'block';
                } else {
                    printServiceEventsOptions.style.display = 'none';
                    printServiceEventCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                }
            });

            checkAllButton.addEventListener('click', function() {
                printServiceEventCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            });

            uncheckAllButton.addEventListener('click', function() {
                printServiceEventCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            });
        });
    </script>
    {{-- time status --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="time_status"]');

            radioButtons.forEach(function(radioButton) {
                radioButton.addEventListener('change', function() {
                    const selectedStatus = this.value;
                    console.log("Selected time status:", selectedStatus);
                });
            });
        });
    </script>
    {{-- add on paket --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addOnSwitch = document.getElementById('add_on_switch');
            const addOnOptions = document.getElementById('add_on_options');

            addOnSwitch.addEventListener('change', function() {
                if (this.checked) {
                    addOnOptions.style.display = 'block';
                } else {
                    addOnOptions.style.display = 'none';
                    resetAddOnInputs();
                }
            });

            const addOnCheckboxes = document.querySelectorAll('.addon-checkbox');
            addOnCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const addonInputs = this.parentNode.querySelector('.addon-inputs');
                    if (this.checked) {
                        addonInputs.style.display = 'block';
                    } else {
                        addonInputs.style.display = 'none';
                        resetAddonInputs(addonInputs);
                    }
                });
            });

            function resetAddOnInputs() {
                const totalInputs = document.querySelectorAll('.addon-inputs');
                totalInputs.forEach(function(input) {
                    resetAddonInputs(input);
                });

                const addOnCheckboxes = document.querySelectorAll('.addon-checkbox');
                addOnCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    const addonInputs = checkbox.parentNode.querySelector('.addon-inputs');
                    addonInputs.style.display = 'none';
                    resetAddonInputs(addonInputs);
                });
            }

            function resetAddonInputs(input) {
                const inputs = input.querySelectorAll('input');
                inputs.forEach(function(input) {
                    input.value = '';
                });
            }

            function collectSelectedAddOns() {
                const selectedAddOns = [];
                const addOnCheckboxes = document.querySelectorAll('.addon-checkbox:checked');
                addOnCheckboxes.forEach(function(checkbox) {
                    const addOnId = checkbox.getAttribute('data-id');
                    const qtyInput = document.getElementById('total_qty_' + addOnId);
                    const qty = qtyInput ? parseInt(qtyInput.value) : 0;
                    selectedAddOns.push({
                        add_on_package_id: addOnId,
                        sum: qty
                    });
                });
                return selectedAddOns;
            }
        });
    </script>
    <script>
        function updateSum(addOnId) {
            var totalQtyInput = document.getElementById('total_qty_' + addOnId);
            var sumInput = document.getElementById('sum_' + addOnId);
            var sum = parseInt(totalQtyInput.value) || 0;
            sumInput.value = sum;
        }
    </script>
    {{-- perkiraan harga --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const estimatedPrice = document.getElementById('estimated_price');
            const packagePriceInput = document.getElementById('package_price');
            const printServiceEventCheckboxes = document.querySelectorAll('input[name="print_photos[]"]');

            function calculateEstimatedPrice() {
                let packagePriceText = packagePriceInput.value.replace(/\s/g, '');
                let packagePrice = parseFloat(packagePriceText || 0);

                let selectedPrintPhotoPrices = [];

                printServiceEventCheckboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        selectedPrintPhotoPrices.push(parseFloat(checkbox.dataset.price));
                    }
                });

                let estimatedPriceText = 'Rp ' + packagePrice.toLocaleString('id-ID');

                if (selectedPrintPhotoPrices.length > 0) {
                    let maxPrintPhotoPrice = Math.max(...selectedPrintPhotoPrices);
                    estimatedPriceText += ' - Rp ' + (packagePrice + maxPrintPhotoPrice).toLocaleString(
                        'id-ID');
                }

                estimatedPrice.textContent = estimatedPriceText;
            }

            // Panggil fungsi saat halaman dimuat
            calculateEstimatedPrice();

            // Panggil fungsi saat checkbox berubah
            printServiceEventCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', calculateEstimatedPrice);
            });

            // Panggil fungsi saat input harga paket berubah
            packagePriceInput.addEventListener('input', calculateEstimatedPrice);
        });
    </script>
    {{-- validasi --}}
    <script>
        function validatePaymentInputs() {
            const dpOption = document.getElementById('dp_option');
            const minPaymentOption = document.getElementById('min_payment_option');
            const dpInput = document.getElementById('dp_input');
            const minPaymentInput = document.getElementById('min_payment_input');
            if (dpOption.checked && dpInput.value.trim() === '') {
                alert('Mohon isi nilai DP');
                return false;
            }
            if (minPaymentOption.checked && minPaymentInput.value.trim() === '') {
                alert('Mohon isi nilai Pembayaran Minimal');
                return false;
            }
            return true;
        }
        document.getElementById('payment_form').addEventListener('submit', function(event) {
            if (!validatePaymentInputs()) {
                event.preventDefault();
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addOnSwitch = document.getElementById('add_on_switch');
            const addOnOptions = document.getElementById('add_on_options');
            addOnSwitch.addEventListener('change', function() {
                if (this.checked) {
                    addOnOptions.style.display = 'block';
                } else {
                    addOnOptions.style.display = 'none';
                }
            });
            const addOnCheckboxes = document.querySelectorAll('input[name="add_ons[]"]');
            addOnCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const totalInputId = 'total_' + this.value;
                    const totalInput = document.getElementById(totalInputId);
                    if (this.checked) {
                        totalInput.style.display = 'block';
                        totalInput.required = true;
                    } else {
                        totalInput.style.display = 'none';
                        totalInput.required = false;
                    }
                });
            });
        });
    </script>
@endpush
