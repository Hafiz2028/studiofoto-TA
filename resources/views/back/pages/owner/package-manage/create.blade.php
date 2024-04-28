@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Add Package')
@section('content')

    <div class="page-header">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-dark">Add Package</h4>
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
                    Add Package
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-primary">Add Package</h4>
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
                    @if (Session::get('success'))
                        <div class="alert alert-success">
                            <strong><i class="dw dw-checked"></i></strong>
                            {!! Session::get('success') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (Session::get('fail'))
                        <div class="alert alert-danger">
                            <strong><i class="dw dw-checked"></i></strong>
                            {!! Session::get('fail') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Nama Paket Foto</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Contoh: Wisuda 1, Diamond 1"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="description">Deskripsi Paket Foto</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="2"
                                        placeholder="Contoh : Paket ini memiliki berbagai macam tambahan foto dan cetak foto dengan berbagai ukuran..."
                                        style="height: 100px;"></textarea>
                                    @error('information')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Maksimal Waktu Pemotretan</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" checked>
                                        <label class="custom-control-label">30 Menit</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input">
                                        <label class="custom-control-label">60 Menit</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input">
                                        <label class="custom-control-label">90 Menit</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input">
                                        <label class="custom-control-label">120 Menit</label>
                                    </div>
                                    @error('time')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="add_on_switch">Add On</label>
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
                                                value="{{ $addOnPackage->id }}">
                                            <label class="custom-control-label"
                                                for="add_on_{{ $addOnPackage->id }}">{{ $addOnPackage->name }}</label>
                                            <div class="addon-inputs" style="display: none;">
                                                <div class="addon-controls">
                                                    <input type="number" id="total_qty_{{ $addOnPackage->id }}"
                                                        name="total_qty_{{ $addOnPackage->id }}"
                                                        class="form-control addon-qty"
                                                        placeholder="Jumlah {{ $addOnPackage->name }} (qty)">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dp_percentage">Metode Pembayaran Tambahan</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="full_payment_option" name="payment_method"
                                            class="custom-control-input" value="full_payment" checked>
                                        <!-- Tambahkan atribut checked di sini -->
                                        <label class="custom-control-label" for="full_payment_option">Hanya Pembayaran
                                            Lunas</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="dp_option" name="payment_method"
                                            class="custom-control-input" value="dp">
                                        <label class="custom-control-label" for="dp_option">DP %</label>
                                        <input type="number" id="dp_input" name="dp_input" class="form-control"
                                            style="display: none;" min="1" max="100">
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="min_payment_option" name="payment_method"
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
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price"
                                        placeholder="Tambahkan Harga Paket..." value="{{ old('price') }}"
                                        required>
                                    <p class="alert alert-info">Jika ada Add On, buat Harga Paket menjadi Harga + Add On.<br>Harga belum termasuk harga Cetak Foto.</p>
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
                                            name="print_photos_switch">
                                        <label class="custom-control-label" for="print_photos_switch">Aktifkan Cetak
                                            Foto</label>
                                    </div>
                                </div>
                                <div id="print_photos_options" style="display: none;">
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
                                                            name="print_photos[]" value="{{ $printServiceEvent->id }}">
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
                                    <label>Perkiraan Harga</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Judul Paket Foto</label>
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
        function togglePaymentInputs() {
            const dpOption = document.getElementById('dp_option');
            const minPaymentOption = document.getElementById('min_payment_option');
            const dpInput = document.getElementById('dp_input');
            const minPaymentInputGroup = document.getElementById('min_payment_input_group');

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

        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentInputs();

            const dpOption = document.getElementById('dp_option');
            const minPaymentOption = document.getElementById('min_payment_option');

            dpOption.addEventListener('change', togglePaymentInputs);
            minPaymentOption.addEventListener('change', togglePaymentInputs);

            const fullPaymentOption = document.getElementById('full_payment_option');
            fullPaymentOption.addEventListener('change', function() {
                const dpInput = document.getElementById('dp_input');
                const minPaymentInputGroup = document.getElementById('min_payment_input_group');

                dpInput.style.display = 'none';
                minPaymentInputGroup.style.display = 'none';
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const minPaymentInput = document.getElementById('min_payment_input');
            minPaymentInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                this.value = value;
            });
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