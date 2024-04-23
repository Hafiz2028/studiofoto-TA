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
                <div class="card-header">Info Venue</div>
                <div class="card-body">
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Studio Foto :</label>
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
                                    <label>No Handphone / WA :</label>
                                    <input type="text"
                                        class="form-control @error('phone_number') is-invalid @enderror"
                                        name="phone_number" placeholder="No Handphone"
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
                                    <label for="information">Deskripsi Venue :</label>
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
                                    <label>Upload Surat Izin Mendirikan Bangunan (IMB) :</label>
                                    <input type="file" class="form-control @error('imb') is-invalid @enderror"
                                        name="imb" wire:model="imb"
                                        @if (!$this->venue->exists || !$this->venue->imb) required @endif>
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
                <div class="card-header">Lokasi Venue</div>
                <div class="card-body">
                    <section id="lokasi_venue" role="tabpanel" aria-labelledby="lokasi_venue" class="tab-panel">
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Alamat:</label>
                                    <input type="text" id="address"
                                        class="form-control @error('address') is-invalid @enderror" name="address"
                                        placeholder="Masukkan alamat" value="{{ old('address') }}"
                                        wire:model.lazy="address">
                                    @error('address')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="kecamatan">Kecamatan:</label>
                                    <select id="kecamatan" class="form-control">
                                        <!-- Options akan diisi oleh JavaScript -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude:</label>
                                    <input type="text" id="latitude"
                                        class="form-control @error('latitude') is-invalid @enderror"
                                        placeholder="Latitude" name="latitude" wire:model="latitude">
                                    @error('latitude')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="longitude">Longitude:</label>
                                    <input type="text" id="longitude"
                                        class="form-control @error('longitude') is-invalid @enderror"
                                        placeholder="Longitude" name="longitude" wire:model="longitude">
                                    @error('longitude')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Koordinat Lokasi:</label>
                                    <div class="col">
                                        <div class="form-group">
                                            {{-- <button class="btn btn-outline-info" wire:click="findMyLocation">My
                                                    Location</button> --}}
                                        </div>
                                        <div wire:ignore>
                                            <div id="map" style="width: 100%; height: 400px;"></div>
                                        </div>
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

                <div class="card-header">Metode Pembayaran</div>
                <div class="card-body">
                    <section id="metode_pembayaran" role="tabpanel" aria-labelledby="metode_pembayaran"
                        class="tab-panel">
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>List Bank:</label>
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
                <div class="card-header">Jadwal Buka Venue</div>
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
                <div class="card-header">Foto Studio & Venue</div>
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
                                                        @if ($index != 0 && $image)
                                                            <button
                                                                class="btn btn-outline-danger btn-sm position-absolute"
                                                                style="top: 5px; right: 5px;" type="button"
                                                                wire:click="removeImage({{ $index }})">
                                                                &times;
                                                            </button>
                                                        @endif
                                                        @if ($image && in_array($image->guessExtension(), ['jpg', 'jpeg', 'png', 'gif']))
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

                                            <!-- Input file untuk gambar -->
                                            @for ($i = 0; $i < count($venueImages); $i++)
                                                <input type="file"
                                                    class="form-control mb-2 @error('venueImages.' . $i) is-invalid @enderror"
                                                    name="venueImages.{{ $i }}"
                                                    wire:model="venueImages.{{ $i }}" required
                                                    style="width: 100%;">
                                                @error('venueImages.' . $i)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endfor

                                            <!-- Jika tidak ada foto, tampilkan satu input file -->
                                            @if (count($venueImages) == 0)
                                                <input type="file" class="form-control mb-2" name="venueImages.0"
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
                                                                    wire:model="venueImages.{{ $imageIndex }}" accept="image/*">
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

    </form>
    @push('styles')
        <style>
            #map {
                width: 100%;
                height: 400px;
                border: 1px solid red;
                /* Add a border for visibility */
            }
        </style>
    @endpush
    @push('scripts')
        {{-- <script src="https://cdn.jsdelivr.net/npm/ol@v7.2.2/dist/ol.js"></script> --}}
        {{-- <script>
            document.addEventListener('livewire:load', function() {
                console.log('Initializing map...');
                const key = 'SzhEAidXbTEomDww4vrj';
                const source = new ol.source.XYZ({
                    url: `https://api.maptiler.com/maps/streets/v1/{z}/{x}/{y}.png?key=${key}`,
                    tileSize: 512,
                    attributions: '<a href="https://www.maptiler.com/copyright/">© MapTiler</a>'
                });
                document.addEventListener('DOMContentLoaded', function() {
                    const map = new ol.Map({
                        layers: [
                            new ol.layer.Tile({
                                source: source
                            })
                        ],
                        target: 'map',
                        view: new ol.View({
                            center: ol.proj.fromLonLat([112.6235743, -
                            7.9147449]),
                            zoom: 13
                        })
                    });
                    console.log('Map initialized successfully');
                    Livewire.on('updateMap', (latitude, longitude) => {
                        addMarker(latitude, longitude);
                    });

                    function addMarker(latitude, longitude) {
                        const marker = new ol.Feature({
                            geometry: new ol.geom.Point(ol.proj.fromLonLat([longitude, latitude]))
                        });

                        const markerLayer = new ol.layer.Vector({
                            source: new ol.source.Vector({
                                features: [marker]
                            })
                        });
                        map.addLayer(markerLayer);
                        document.getElementById('latitude').value = latitude.toFixed(4);
                        document.getElementById('longitude').value = longitude.toFixed(4);
                    }
                });
            });
        </script> --}}

        {{-- <script>
            document.addEventListener('livewire:load', function() {
                const apiKey = "SzhEAidXbTEomDww4vrj"; // Ganti dengan API key Anda
                const map = new OpenLayers.Map("map");
                const mapnik = new OpenLayers.Layer.OSM();
                const epsg4326 = new OpenLayers.Projection("EPSG:4326"); // Proyeksi WGS 1984
                const projectTo = new OpenLayers.Projection("EPSG:900913"); // Proyeksi Spherical Mercator

                map.addLayer(mapnik);
                map.setCenter(
                    new OpenLayers.LonLat(0, 0).transform(epsg4326, projectTo),
                    2
                );

                // Tambahkan layer vector tiles
                const vectorLayer = new OpenLayers.Layer.Vector("Vector Tiles", {
                    strategies: [new OpenLayers.Strategy.Fixed()],
                    protocol: new OpenLayers.Protocol.HTTP({
                        url: `https://api.maptiler.com/tiles/v3-openmaptiles/{z}/{x}/{y}.pbf?key=${apiKey}`,
                        format: new OpenLayers.Format.MVT(),
                    }),
                });

                map.addLayer(vectorLayer);
            });
        </script> --}}
    @endpush
</div>
