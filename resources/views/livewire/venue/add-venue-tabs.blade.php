<div>


    {{-- <div class="profile-tab height-100-p">
        <div class="tab height-100-p">
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a wire:click.prevent='selectTab("info_venue")' class="nav-link {{ $tab == 'info_venue' ? 'active' : '' }}" data-toggle="tab" href="#info_venue" role="tab">Info Venue</a>
                </li>
                <li class="nav-item">
                    <a wire:click.prevent='selectTab("lokasi_venue")' class="nav-link {{ $tab == 'lokasi_venue' ? 'active' : '' }}" data-toggle="tab" href="#lokasi_venue" role="tab">Lokasi Venue</a>
                </li>
                <li class="nav-item">
                    <a wire:click.prevent='selectTab("update_password")' class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab" href="#update_password" role="tab">Update Password</a>
                </li>
                <li class="nav-item">
                    <a wire:click.prevent='selectTab("update_password")' class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab" href="#update_password" role="tab">Update Password</a>
                </li>
                <li class="nav-item">
                    <a wire:click.prevent='selectTab("update_password")' class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab" href="#update_password" role="tab">Update Password</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Timeline Tab start -->
                <div class="tab-pane fade {{ $tab == 'info_venue' ? 'active show' : '' }}" id="info_venue" role="tabpanel">
                    <div class="pd-20">
                        <form wire:submit.prevent='updateOwnerPersonalDetails'>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type='text' class="form-control" wire:model='name' placeholder='Enter full name'>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type='text' class="form-control" wire:model='email' placeholder='Enter Email'>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Username</label>
                                        <input type='text' class="form-control" wire:model='username' placeholder='Enter Username'>
                                        @error('username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                <!-- Timeline Tab End -->
                <!-- Tasks Tab start -->
                <div class="tab-pane fade {{ $tab == 'update_password' ? 'active show' : '' }}" id="update_password" role="tabpanel">
                    <div class="pd-20 profile-task-wrap">
                        <form wire:submit.prevent='updatePassword()'>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Current Password</label>
                                        <input type="password" placeholder="Enter current password"
                                        wire:model.defer='current_password' class="form-control">
                                        @error('current_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">New Password</label>
                                        <input type="password" placeholder="Enter new password"
                                        wire:model.defer='new_password'class="form-control">
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Confirm new Password</label>
                                        <input type="password" placeholder="Retype new password"
                                        wire:model.defer='new_password_confirmation' class="form-control">
                                        @error('new_password_confirmation')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
                <!-- Tasks Tab End -->
                <!-- Tasks Tab start -->
                <div class="tab-pane fade {{ $tab == 'update_password' ? 'active show' : '' }}" id="update_password" role="tabpanel">
                    <div class="pd-20 profile-task-wrap">
                        <form wire:submit.prevent='updatePassword()'>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Current Password</label>
                                        <input type="password" placeholder="Enter current password"
                                        wire:model.defer='current_password' class="form-control">
                                        @error('current_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">New Password</label>
                                        <input type="password" placeholder="Enter new password"
                                        wire:model.defer='new_password'class="form-control">
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Confirm new Password</label>
                                        <input type="password" placeholder="Retype new password"
                                        wire:model.defer='new_password_confirmation' class="form-control">
                                        @error('new_password_confirmation')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
                <!-- Tasks Tab End -->
                <!-- Tasks Tab start -->
                <div class="tab-pane fade {{ $tab == 'update_password' ? 'active show' : '' }}" id="update_password" role="tabpanel">
                    <div class="pd-20 profile-task-wrap">
                        <form wire:submit.prevent='updatePassword()'>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Current Password</label>
                                        <input type="password" placeholder="Enter current password"
                                        wire:model.defer='current_password' class="form-control">
                                        @error('current_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">New Password</label>
                                        <input type="password" placeholder="Enter new password"
                                        wire:model.defer='new_password'class="form-control">
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Confirm new Password</label>
                                        <input type="password" placeholder="Retype new password"
                                        wire:model.defer='new_password_confirmation' class="form-control">
                                        @error('new_password_confirmation')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
                <!-- Tasks Tab End -->
                <!-- Tasks Tab start -->
                <div class="tab-pane fade {{ $tab == 'update_password' ? 'active show' : '' }}" id="update_password" role="tabpanel">
                    <div class="pd-20 profile-task-wrap">
                        <form wire:submit.prevent='updatePassword()'>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Current Password</label>
                                        <input type="password" placeholder="Enter current password"
                                        wire:model.defer='current_password' class="form-control">
                                        @error('current_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">New Password</label>
                                        <input type="password" placeholder="Enter new password"
                                        wire:model.defer='new_password'class="form-control">
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Confirm new Password</label>
                                        <input type="password" placeholder="Retype new password"
                                        wire:model.defer='new_password_confirmation' class="form-control">
                                        @error('new_password_confirmation')
                                            <span class="text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
                <!-- Tasks Tab End -->
            </div>
        </div>
    </div> --}}




    <form wire:submit.prevent='storeVenue' class="">
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
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nama Venue" value="{{ old('name') }}" wire:model="name">
                                        @error('name')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No Handphone / WA :</label>
                                        <input type="text" class="form-control" name="phone_number"
                                            placeholder="No Handphone" value="{{ old('phone_number') }}"
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
                                            placeholder="Isi deskripsi untuk venue" wire:model="information"></textarea>
                                        @error('information')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Upload Surat Izin Mendirikan Bangunan (IMB) :</label>
                                        <input type="file" class="form-control" name="imb"
                                            placeholder="format .pdf" wire:model="imb">
                                        @error('imb')
                                            <span class="text-danger ml-2">{{ $message }}</span>
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
                                        <input type="text" id="address" class="form-control" name="address"
                                            placeholder="Masukkan alamat" value="{{ old('address') }}"
                                            wire:model="address">
                                        @error('address')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude">Latitude:</label>
                                        <input type="number" id="latitude" class="form-control" placeholder="Latitude"
                                            readonly name="latitude" wire:model="latitude">
                                        @error('latitude')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="longitude">Longitude:</label>
                                        <input type="number" id="longitude" class="form-control"
                                            placeholder="Longitude" name="longitude" readonly wire:model="longitude">
                                        @error('longitude')
                                            <span class="text-danger ml-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Koordinat Lokasi:</label>
                                        <div class="col">
                                            <div class="form-group" id="map"></div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <button class="btn btn-outline-info" id="myLocationButton">My
                                                    Location</button>
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
                                                            wire:click="toggleBankAccountInput('{{ $payment_method->id }}')">
                                                        <label class="form-check-label"
                                                            for="bank{{ $payment_method->id }}">
                                                            <img src="{{ asset('images/icon_bank/' . $payment_method->icon) }}"
                                                                alt="{{ $payment_method->name }}" width="24"
                                                                height="24">
                                                            {{ $payment_method->name }}
                                                        </label>
                                                        <input type="text"
                                                            id="bankAccount{{ $payment_method->id }}"
                                                            class="form-control"
                                                            style="{{ $selectedPaymentMethod[$payment_method->id] ? '' : 'display: none;' }}"
                                                            placeholder="Nomor Rekening / E-Wallet"
                                                            wire:model="bank_accounts.{{ $payment_method->id }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
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
                            <br>
                            <div class="row">
                                @foreach ($days as $index => $day)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="{{ strtolower($day->name) }}-toggle"
                                                    wire:model="jadwal_hari.{{ $day->id }}"
                                                    wire:click="toggleDaySchedule('{{ $day->id }}')">
                                                <label class="custom-control-label"
                                                    for="{{ strtolower($day->name) }}-toggle">{{ $day->name }}</label>
                                            </div>
                                            <div id="{{ strtolower($day->name) }}-schedule"
                                                style="margin-top: 10px;">
                                                @if (isset($jadwal_hari[$day->id]) && $jadwal_hari[$day->id])
                                                    <div class="form-check" style="display: flex; flex-wrap: wrap;">
                                                        @foreach ($hours as $hour)
                                                            <div style="width: 16.66%; margin-bottom: 5px;">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="{{ strtolower($day->name) }}-{{ $hour->id }}"
                                                                    value="{{ $hour->time }}"
                                                                    wire:model="jadwal_jam.{{ $day->id }}.{{ $hour->id }}">
                                                                <label class="form-check-label"
                                                                    for="{{ strtolower($day->name) }}-{{ $hour->id }}">{{ $hour->hour }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div style="margin-top: 10px;">
                                                        <button class="btn btn-outline-success"
                                                            wire:click="checkAll('{{ $day->id }}')"
                                                            data-toggle="tooltip"
                                                            title="Ceklis semua jadwal hari ini"><i
                                                                class="bi bi-check-all"></i></button>
                                                        <button class="btn btn-outline-danger"
                                                            wire:click="uncheckAll('{{ $day->id }}')"
                                                            data-toggle="tooltip"
                                                            title="Hapus semua jadwal hari ini"><i
                                                                class="fa fa-trash"></i></button>
                                                        <button class="btn btn-outline-primary"
                                                            wire:click="checkWorkingHours('{{ $day->id }}')"
                                                            data-toggle="tooltip"
                                                            title="Ceklis jadwal hari ini berdasarkan jam kerja"><i
                                                                class="fa fa-briefcase"></i></button>
                                                        @if (isset($days[$index + 1]))
                                                            <button class="btn btn-outline-info"
                                                                wire:click="copySchedule('{{ $day->id }}', '{{ $days[$index + 1]->id }}')"
                                                                data-toggle="tooltip"
                                                                title="Salin semua jadwal hari ini ke hari {{ ucfirst(strtolower($days[$index + 1]->name)) }}"><i
                                                                    class="fa fa-copy"></i></button>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        @elseif ($currentStep == 5)
            <div class="step-five">
                <div class="card">
                    <div class="card-header">Foto Venue</div>
                    <div class="card-body">
                        <section id="foto_venue" role="tabpanel" aria-labelledby="foto_venue" class="tab-panel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Foto Background / Profile Venue</label>
                                        <input type="file" class="form-control" wire:model="picture" />
                                    </div>
                                    <div class="form-group" id="additionalPhotos">
                                        <label>Foto Gedung atau Layanan Venue</label>
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control" id="additionalPhotoInput"
                                                wire:model="venue_image">
                                            <button class="btn btn-outline-primary" type="button"
                                                onclick="addAdditionalPhoto()">Tambah
                                                Foto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
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
                <button type="submit" class="btn btn-primary">Submit</button>
            @endif
        </div>

    </form>
</div>
