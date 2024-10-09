<div>
    @if ($venue && $venue->exists)
        <form wire:submit.prevent="updateVenue({{ $venue->id }})">
        @else
            <form wire:submit.prevent="storeVenue">
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">
            <strong><i class="dw dw-checked"></i></strong>
            {!! session('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('fail'))
        <div class="alert alert-danger">
            <strong><i class="dw dw-checked"></i></strong>
            {!! session('fail') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($currentStep == 1)
        <div class="step-one">
            <div class="card">
                <div class="card-header"><strong>A Info Venue</strong></div>
                <div class="card-body">
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>1. Nama Studio Foto :</strong></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Nama Venue"
                                        @if ($this->venue->exists) value="{{ $name }}" @else value="{{ old('name') }}" @endif
                                        wire:model="name">
                                    @error('name')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>2. No Handphone / WA :</strong></label>
                                    <input type="text"
                                        class="form-control @error('phone_number') is-invalid @enderror"
                                        name="phone_number" placeholder="Nomor yang terdaftar di Aplikasi WhatsApp..."
                                        @if ($this->venue->exists) value="{{ $phone_number }}" @else value="{{ old('phone_number') }}" @endif
                                        wire:model="phone_number">
                                    @error('phone_number')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="information"><strong>3. Deskripsi Venue :</strong></label>
                                    <textarea class="form-control" id="information" name="information" rows="4"
                                        placeholder="Isi deskripsi untuk venue" wire:model="information">
                                        </textarea>
                                    @error('information')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>4. Surat Nomor Induk Berusaha (NIB) :</strong></label>

                                    <input type="file" class="form-control @error('imb') is-invalid @enderror"
                                        name="imb" wire:model="imb"
                                        @if (!$this->venue->exists || !$this->venue->imb) required @endif>
                                    <small> Format file .pdf & max size 2.048 kb</small>
                                    @if ($this->venue->exists && $this->venue->imb)
                                        <p>{{ $this->venue->imb }}</p>
                                    @endif
                                    @error('imb')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @elseif ($currentStep == 2)
        <div class="step-two">
            <div class="card">
                <div class="card-header"><strong>B Lokasi Venue</strong></div>
                <div class="card-body">
                    <section id="lokasi_venue" role="tabpanel" aria-labelledby="lokasi_venue" class="tab-panel">
                        <br>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="district"><strong>1. Kecamatan :</strong></label>
                                        <select id="districtSelect" name="district_id" wire:model="selectedDistrictId"
                                            wire:change="getVillages" class="form-control">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('district_id')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="village"><strong>2. Kelurahan :</strong></label>
                                        <select id="villageSelect" name="village_id" wire:model.defer="village_id"
                                            wire:change="saveVillageId($event.target.value)" class="form-control">
                                            <option value="">Pilih Kelurahan</option>
                                            @foreach ($villages as $village)
                                                <option value="{{ $village->id }}">{{ $village->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('village_id')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="address"><strong>3. Alamat Detail :</strong></label>
                                        <input type="text" id="address"
                                            class="form-control @error('address') is-invalid @enderror" name="address"
                                            placeholder="Patokan Venue, seperti nama jalan atau bangunan terkenal..."
                                            value="{{ old('address') }}" wire:model.lazy="address">
                                        @error('address')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="map_link"><strong>4. Link Google Maps :</strong></label>
                                        <input type="text" id="map_link"
                                            class="form-control @error('map_link') is-invalid @enderror"
                                            placeholder="Tambahkan Link Lokasi Venue pada Google Maps..."
                                            name="map_link" wire:model="map_link">
                                        @error('map_link')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @elseif ($currentStep == 3)
        <div class="step-three">
            <div class="card">

                <div class="card-header"><strong>C Metode Pembayaran</strong></div>
                <div class="card-body">
                    <section id="metode_pembayaran" role="tabpanel" aria-labelledby="metode_pembayaran"
                        class="tab-panel">
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>1. List Bank :</strong></label>
                                    <div id="bankList" style="display: flex; flex-wrap: wrap;">
                                        @foreach ($payment_methods as $payment_method)
                                            <div style="width: 50%; margin-bottom: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="bank{{ $payment_method->id }}"
                                                        wire:click="toggleBankAccountInput('{{ $payment_method->id }}')"
                                                        @if (isset($selectedPaymentMethod[$payment_method->id]) && $selectedPaymentMethod[$payment_method->id]) checked @endif>
                                                    <label class="form-check-label"
                                                        for="bank{{ $payment_method->id }}">
                                                        <img src="{{ asset('images/icon_bank/' . $payment_method->icon) }}"
                                                            alt="{{ $payment_method->name }}" width="24"
                                                            height="24">
                                                        {{ $payment_method->name }}
                                                    </label>
                                                    <input type="text" id="bankAccount{{ $payment_method->id }}"
                                                        class="form-control @error('bank_accounts.' . $payment_method->id) is-invalid @enderror"
                                                        style="{{ isset($selectedPaymentMethod[$payment_method->id]) && $selectedPaymentMethod[$payment_method->id] ? '' : 'display: none;' }}"
                                                        placeholder="Nomor Rekening / E-Wallet"
                                                        wire:model="bank_accounts.{{ $payment_method->id }}"
                                                        @if (isset($selectedPaymentMethod[$payment_method->id]) && $selectedPaymentMethod[$payment_method->id]) required @endif>
                                                    @error('bank_accounts.' . $payment_method->id)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    {{-- @if ($currentStep == 3 && $errors->has('bank_accounts'))
                                            <div class="alert alert-danger mt-2">
                                                {{ $errors->first('bank_accounts') }}
                                            </div>
                                        @endif --}}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

            </div>
        </div>
    @elseif ($currentStep == 4)
        <div class="step-four">
            <div class="card">
                <div class="card-header"><strong>D Jadwal Buka Venue</strong></div>
                <div class="card-body">
                    <section id="jadwal_buka" role="tabpanel" aria-labelledby="jadwal_buka" class="tab-panel">
                        <div class="row">
                            @foreach ($days as $index => $day)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex flex-column">
                                            <label class="font-weight-bold">{{ $day->name }}</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="day{{ $day->id }}"
                                                    wire:click="toggleDaySchedule('{{ $day->id }}')"
                                                    {{ isset($this->selectedOpeningDay[$day->id]) && $this->selectedOpeningDay[$day->id] ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="day{{ $day->id }}">{{ $selectedOpeningDay[$day->id] ? 'Buka' : 'Tutup' }}</label>
                                            </div>
                                        </div>
                                        <div id="{{ strtolower($day->name) }}-schedule" style="margin-top: 10px;">
                                            @if (isset($this->selectedOpeningDay[$day->id]) && $this->selectedOpeningDay[$day->id])
                                                <div class="alert alert-warning">
                                                    Jadwal Hari {{ $day->name }} <strong>TIDAK TERSIMPAN &
                                                        DIRESET</strong> jika <strong>DITUTUP</strong>.
                                                </div>
                                                @if ($errors->has('opening_hours.*'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('opening_hours.*') }}
                                                    </div>
                                                @endif
                                                @if ($errors->has('opening_hours.*.*'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('opening_hours.*.*') }}
                                                    </div>
                                                @endif
                                                <div style="margin-top: 10px; margin-bottom: 10px;">
                                                    <button class="btn btn-outline-success"
                                                        wire:click.prevent="checkAll('{{ $day->id }}')"
                                                        data-toggle="tooltip" title="Ceklis semua jadwal hari ini"><i
                                                            class="bi bi-check-all"></i></button>
                                                    <button class="btn btn-outline-danger"
                                                        wire:click.prevent="uncheckAll('{{ $day->id }}')"
                                                        data-toggle="tooltip" title="Hapus semua jadwal hari ini"><i
                                                            class="fa fa-trash"></i></button>
                                                    <button class="btn btn-outline-primary"
                                                        wire:click.prevent="checkWorkingHours('{{ $day->id }}')"
                                                        data-toggle="tooltip"
                                                        title="Ceklis jadwal hari ini berdasarkan jam kerja"><i
                                                            class="fa fa-briefcase"></i></button>
                                                    @if (isset($days[$index + 1]))
                                                        <button class="btn btn-outline-info"
                                                            wire:click.prevent="copySchedule('{{ $day->id }}', '{{ $days[$index + 1]->id }}')"
                                                            data-toggle="tooltip"
                                                            title="Salin semua jadwal dari hari {{ ucfirst(strtolower($day->name)) }} ke hari {{ ucfirst(strtolower($days[$index + 1]->name)) }}"><i
                                                                class="fa fa-copy"></i></button>
                                                    @endif
                                                </div>
                                                <div class="form-check"
                                                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 5px; margin-bottom: 10px; margin-top:10px;">
                                                    @foreach ($hours as $hour)
                                                        <div>
                                                            <input class="form-check-input" type="checkbox"
                                                                id="openingHours{{ $day->id }}{{ $hour->id }}"
                                                                value="{{ $hour->time }}"
                                                                wire:model="opening_hours.{{ $day->id }}.{{ $hour->id }}"
                                                                {{ $this->selectedOpeningDay[$day->id] ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="openingHours{{ $day->id }}{{ $hour->id }}">{{ $hour->hour }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @foreach ($this->getErrorBag()->get('opening_hours') as $error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endforeach
                        <div class="alert alert-warning">
                            Jadwal Hari <strong>TIDAK DISIMPAN</strong> jika dibiarkan <strong>TERBUKA &
                                KOSONG</strong>.
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @elseif ($currentStep == 5)
        <div class="step-five">
            <div class="card">
                <div class="card-header"><strong>E Foto Studio & Venue</strong></div>
                <div class="card-body">
                    <section id="foto_venue" role="tabpanel" aria-labelledby="foto_venue" class="tab-panel">
                        @if (!$this->venue->exists)
                            {{-- kondisi createVenue --}}
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 col-lg-12">
                                        <div class="form-group">
                                            <label><strong>Upload Foto Venue</strong></label>
                                            <div class="d-flex mb-2" style="max-width: 100%; overflow-x: auto;">
                                                @foreach ($venueImages as $index => $image)
                                                    <div class="mr-2 position-relative">
                                                        @if ($index != 0)
                                                            <button
                                                                class="btn btn-outline-danger btn-sm position-absolute"
                                                                style="top: 5px; right: 5px;" type="button"
                                                                wire:click="removeImage({{ $index }})">
                                                                &times;
                                                            </button>
                                                        @endif
                                                        @if ($image)
                                                            <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                                                style="width: 150px; height: auto;">
                                                        @else
                                                            <div class="alert alert-danger">
                                                                <p>File tidak dapat dipratinjau</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @for ($i = 0; $i < count($venueImages); $i++)
                                                <input type="file"
                                                    class="form-control mb-2 @error('venueImages.' . $i) is-invalid @enderror"
                                                    wire:model="venueImages.{{ $i }}" required
                                                    style="width: 100%;">
                                                @error('venueImages.' . $i)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endfor
                                            @if (count($venueImages) == 0)
                                                <input type="file" class="form-control mb-2"
                                                    wire:model="venueImages.0" required style="width: 100%;">
                                            @endif
                                            <!-- Tombol untuk menambah foto -->
                                            <button class="btn btn-outline-primary m-3" type="button"
                                                wire:click="addImage">Tambah Foto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end createVenue --}}
                        @else
                            {{-- kondisi updateVenue --}}
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 col-lg-12">
                                        <div class="form-group">
                                            <label><strong>Update Foto Venue</strong></label>
                                            <div class="row">
                                                @foreach ($venueImages as $imageIndex => $image)
                                                    <div class="col-md-3 col-sm-6 mb-3">
                                                        <div class="position-relative">
                                                            @if (is_array($image))
                                                                @php
                                                                    $imageName = $image['name'] ?? null;
                                                                    $imagePath = public_path(
                                                                        'images/venues/Venue_Image/' . $imageName,
                                                                    );
                                                                @endphp
                                                                @if (is_string($imageName) && file_exists($imagePath))
                                                                    <img src="{{ asset('images/venues/Venue_Image/' . $imageName) }}"
                                                                        alt="Venue Image" class="img-thumbnail mb-2">
                                                                    <button class="btn btn-outline-danger btn-sm w-100"
                                                                        type="button"
                                                                        wire:click="removeImage({{ $imageIndex }})">
                                                                        Hapus
                                                                    </button>
                                                                @else
                                                                    <span class="text-danger">Gambar tidak
                                                                        ditemukan</span>
                                                                @endif
                                                            @else
                                                                @if ($image)
                                                                    <img src="{{ $image->temporaryUrl() }}"
                                                                        alt="Preview Image"
                                                                        class="img-thumbnail mb-2">
                                                                @endif
                                                                <input type="file"
                                                                    class="form-control @error('venueImages.' . $imageIndex) is-invalid @enderror"
                                                                    name="venueImages.{{ $imageIndex }}"
                                                                    wire:model="venueImages.{{ $imageIndex }}"
                                                                    accept="image/*">
                                                                <button
                                                                    class="btn btn-outline-danger btn-sm mt-2 w-100"
                                                                    type="button"
                                                                    wire:click="removeImage({{ $imageIndex }})">
                                                                    Hapus
                                                                </button>
                                                                @error('venueImages.' . $imageIndex)
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button class="btn btn-outline-primary mt-3" type="button"
                                                wire:click="addImage">Tambah Foto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end updateVenue --}}
                        @endif
                    </section>
                </div>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($venue && $venue->exists)
        <div class="actions-buttons d-flex justify-content-between bg-white pt-2 pb-2">
            @if ($currentStep == 1)
                <div></div>
                <button type="submit"
                    class="btn btn-primary btn-lg">{{ $venue && $venue->exists ? 'Update' : 'Submit' }}</button>
                <button type="button" class="btn btn-outline-success" wire:click="increaseStep()">Next</button>
            @endif

            @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4 || $currentStep == 5)
                <button type="button" class="btn btn-outline-secondary" wire:click="decreaseStep()">Back</button>
                <button type="submit"
                    class="btn btn-primary btn-lg">{{ $venue && $venue->exists ? 'Update' : 'Submit' }}</button>
            @endif

            @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4)
                <button type="button" class="btn btn-outline-success" wire:click="increaseStep()">Next</button>
            @endif

        </div>
    @else
        <div class="actions-buttons d-flex justify-content-between bg-white pt-2 pb-2">
            @if ($currentStep == 1)
                <div></div>
            @endif

            @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4 || $currentStep == 5)
                <button type="button" class="btn btn-outline-secondary" wire:click="decreaseStep()">Back</button>
            @endif

            @if ($currentStep == 1 || $currentStep == 2 || $currentStep == 3 || $currentStep == 4)
                <button type="button" class="btn btn-outline-success" wire:click="increaseStep()">Next</button>
            @endif

            @if ($currentStep == 5)
                <button type="submit" class="btn btn-primary">
                    {{ $venue && $venue->exists ? 'Update' : 'Submit' }}
                </button>
            @endif
        </div>
    @endif



    </form>
    @push('styles')
        <style>
        </style>
    @endpush
    @push('scripts')
    @endpush
</div>
