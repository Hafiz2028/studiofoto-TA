@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Tambah Paket Foto')
@section('content')

    <div class="page-header">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-dark">Tambah Paket Foto</h4>
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
                    Tambah Paket Foto
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-primary">Tambah Paket Foto</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('owner.venue.services.show', ['venue' => $venue->id, 'service' => $service->id]) }}"
                            class="btn btn-outline-info btn-sm">
                            <i class="ion-arrow-left-a"></i> Kembali
                        </a>
                    </div>
                </div>
                <hr>

                <form id="payment_form"
                    action="{{ route('owner.venue.services.packages.store', ['venue' => $venue->id, 'service' => $service->id]) }}"
                    method="POST" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    @csrf
                    {{-- <x-alert.form-alert /> --}}
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name"><strong>1. Nama Paket Foto</strong></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Contoh: Wisuda 1, Diamond 1"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="information"><strong>2. Deskripsi Paket Foto</strong></label>
                                    <textarea class="form-control @error('information') is-invalid @enderror" id="information" name="information"
                                        rows="4"
                                        placeholder="Contoh : Paket ini memiliki berbagai macam tambahan foto dan cetak foto dengan berbagai ukuran..."
                                        style="height: 130px;"></textarea>
                                    @error('information')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dp_percentage"><strong>3. Metode Pembayaran Tambahan</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="full_payment_option" name="dp_percentage"
                                            class="custom-control-input" value="full_payment" checked>
                                        <label class="custom-control-label" for="full_payment_option">Hanya Pembayaran
                                            Lunas</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="dp_option" name="dp_percentage"
                                            class="custom-control-input" value="dp">
                                        <label class="custom-control-label" for="dp_option">DP %</label>
                                        <input type="number" id="dp_input" name="dp_input" class="form-control"
                                            style="display: none;" min="1" max="100">
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="min_payment_option" name="dp_percentage"
                                            class="custom-control-input" value="min_payment">
                                        <label class="custom-control-label" for="min_payment_option">Minimal
                                            Pembayaran</label>
                                        <div id="min_payment_input_group" style="display: none;">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" id="min_payment_input" name="min_payment_input"
                                                    class="form-control">
                                            </div>
                                            @error('min_payment_input')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    @error('dp_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label for="add_on_switch"><strong>4. Add On (Optional)</strong></label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="add_on_switch"
                                            name="add_on_switch">
                                        <label class="custom-control-label" for="add_on_switch">Aktifkan Tambahan</label>
                                    </div>
                                </div>
                                <div id="add_on_options" style="display: none;">
                                    <label for="add_ons">Tambahkan Total Add On untuk paket ini </label><br>
                                    @foreach ($addOnPackages as $addOnPackage)
                                        <div class="addon-item custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input addon-checkbox"
                                                id="add_on_{{ $addOnPackage->id }}" name="add_ons[]"
                                                value="{{ $addOnPackage->id }}" data-id="{{ $addOnPackage->id }}">
                                            <label class="custom-control-label"
                                                for="add_on_{{ $addOnPackage->id }}">{{ $addOnPackage->name }}</label>
                                            <div class="addon-inputs" style="display: none;">
                                                <div class="addon-controls">
                                                    <input type="number" id="total_qty_{{ $addOnPackage->id }}"
                                                        name="total_qty_{{ $addOnPackage->id }}"
                                                        class="form-control addon-qty"
                                                        placeholder="Jumlah {{ $addOnPackage->name }} (qty)"
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
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label><strong>5. Cetak Foto (Optional)</strong></label>
                                    <select class="custom-select2 form-control" multiple="multiple"
                                        id="print_photo_details" name="print_photo_details[]">
                                        @foreach ($printPhotos as $printPhoto)
                                            <option value="{{ $printPhoto->id }}">Size {{ $printPhoto->size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label><strong>6. Frame (Optional)</strong></label>
                                    <select class="custom-select2 form-control" multiple="multiple"
                                        id="frame_photo_details" name="frame_photo_details[]">
                                        @foreach ($printPhotos as $printPhoto)
                                            @if ($printPhoto->id >= 4)
                                                <option value="{{ $printPhoto->id }}">Size {{ $printPhoto->size }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for=""><strong>7. Paket Harga & Waktu Pemotretan</strong></label>
                                <div class="form-group mb-0">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6">
                                            <label for="price">Harga Paket</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" id="price" name="prices[]"
                                                    class="form-control @error('prices.*') is-invalid @enderror"
                                                    placeholder="Harga Paket..." value="{{ old('prices.0') }}">
                                                @error('prices.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="time_status">Maks Waktu Foto</label>
                                                <select class="form-control @error('time_status.*') is-invalid @enderror"
                                                    id="time_status" name="time_status[]">
                                                    <option value="" selected>Pilih Maksimal Waktu Foto...
                                                    </option>
                                                    <option value="0">30 Menit</option>
                                                    <option value="1">60 Menit</option>
                                                    <option value="2">90 Menit</option>
                                                    <option value="3">120 Menit</option>
                                                </select>
                                                @error('time_status.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <label for="people_sum">Total Orang</label>
                                            <div class="input-group">
                                                <input type="text" id="people_sum" name="people_sums[]"
                                                    class="form-control @error('people_sums.*') is-invalid @enderror"
                                                    placeholder="e.g: 1, 1 - 5, 4 - 10..."
                                                    value="{{ old('people_sums.0') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Orang</span>
                                                </div>
                                                @error('people_sums.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group new-price-person">
                                    <button type="button" class="btn btn-outline-info mb-1"
                                        onclick="addNewInputGroup()">Tambah
                                        Harga Baru</button>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary float-right">Tambah Paket</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
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
    {{-- input harga baru --}}
    <script>
        var inputIndex = 1;

        function addNewInputGroup() {
            var newInputGroup = `
            <div class="row align-items-center">
                <div class="col-md-4 col-sm-6">
                    <label for="price">Harga Paket</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" id="price${inputIndex}" name="prices[]" class="form-control @error('prices.*') is-invalid @enderror" placeholder="Harga Paket..." value="{{ old('prices.${inputIndex}') }}" required>
                    </div>
                    @error('prices.*')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                <div class="form-group">
                <label for="time_status">Maks Waktu Foto</label>
                <select class="form-control" id="time_status${inputIndex}" name="time_status[]"
                required>
                <option value="" disabled selected>Pilih Maksimal Waktu Foto...
                </option>
                <option value="0">30 Menit</option>
                <option value="1">60 Menit</option>
                <option value="2">90 Menit</option>
                <option value="3">120 Menit</option>
                </select>
                @error('time_status.*')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <label for="people_sum">Total Orang</label>
                    <div class="input-group">
                        <input type="text" id="people_sum${inputIndex}" name="people_sums[]" class="form-control @error('people_sums.*') is-invalid @enderror" placeholder="Contoh: 1, 1 - 5, 4 - 10..." value="{{ old('people_sums.${inputIndex}') }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text">Orang</span>
                        </div>
                    </div>
                    @error('people_sums.*')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-1 col-sm-2">
                    <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center" onclick="removeInputGroup(this)"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        `;

            // Append the new input group to the parent container
            $('.form-group.new-price-person').append(newInputGroup);
            inputIndex++;
        }

        function removeInputGroup(button) {
            $(button).closest('.row').remove();
        }
    </script>
    {{-- metode pembayaran  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dpOption = document.getElementById('dp_option');
            const minPaymentOption = document.getElementById('min_payment_option');
            const dpInput = document.getElementById('dp_input');
            const minPaymentInputGroup = document.getElementById('min_payment_input_group');

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

            dpOption.addEventListener('change', togglePaymentInputs);
            minPaymentOption.addEventListener('change', togglePaymentInputs);

            togglePaymentInputs();

            const fullPaymentOption = document.getElementById('full_payment_option');
            fullPaymentOption.addEventListener('change', function() {
                dpInput.style.display = 'none';
                minPaymentInputGroup.style.display = 'none';
                priceInput.value = '';
            });
            document.querySelectorAll('input[name="dp_percentage"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'dp') {
                        document.getElementById('dp_input').style.display = 'block';
                        document.getElementById('min_payment_input_group').style.display = 'none';
                    } else if (this.value === 'min_payment') {
                        document.getElementById('dp_input').style.display = 'none';
                        document.getElementById('min_payment_input_group').style.display = 'block';
                    } else {
                        document.getElementById('dp_input').style.display = 'none';
                        document.getElementById('min_payment_input_group').style.display = 'none';
                    }
                });
            });
            if (!dpOption.checked) {
                dpInput.value = '';
            }
            if (!minPaymentOption.checked) {
                priceInput.value = '';
            }
        });
    </script>
    {{-- time status --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="time_status[]"]');

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
