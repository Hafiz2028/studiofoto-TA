<div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-section">
                    <div class="profile-photo">
                        <a href="javascript:;"
                            onclick="event.preventDefault();document.getElementById('ownerProfilePictureFile').click();"
                            class="edit-avatar"><i class="fas fa-pencil-alt"></i></a>
                        <img src="{{ $owner->picture }}" alt="" class="avatar-photo" id="ownerProfilePicture">
                        <input type="file" name="ownerProfilePictureFile" id="ownerProfilePictureFile" class="d-none"
                            style="opacity:0">
                    </div>
                    <h5 class="text-center h5 mb-0" id="ownerProfileName">{{ $owner->name }}</h5>
                    <p class="text-center text-muted font-14" id="ownerProfileEmail">{{ $owner->email }}</p>
                </div>
                {{-- INPUT KTP untuk owner --}}
                <div class="divider"></div>
                <div class="ktp-section text-center">
                    <div class="ktp-photo" style="position: relative;">
                        <a href="javascript:;"
                            onclick="event.preventDefault();document.getElementById('ownerKtpImageFile').click();"
                            class="edit-avatar"
                            style="position: absolute; top: 0; right: 0; background-color: #757575; border-radius: 50%; padding: 5px; transition: background-color 0.3s, color 0.3s;"
                            onmouseover="this.style.backgroundColor='white'; this.querySelector('i').style.color='#757575';"
                            onmouseout="this.style.backgroundColor='#757575'; this.querySelector('i').style.color='white';">
                            <i class="fas fa-pencil-alt"
                                style="color: white; font-size: 20px; width: 30px; height: 30px; display: inline-flex; justify-content: center; align-items: center;"></i>
                        </a>
                        <img src="{{ $owner->ktp }}" alt="" class="avatar-photo" id="ownerKtpImage">
                        <input type="file" name="ownerKtpImageFile" id="ownerKtpImageFile" class="d-none"
                            style="opacity:0;">
                    </div>
                    <h5 class="text-center h5 my-2">KTP Owner</h5>
                    @if ($owner->ktp !== 'http://studiofoto.test/images/users/owners/KTP_owner/ktp.png')
                        <p class="alert alert-success mb-4">Ada</p>
                    @else
                        <p class="alert alert-danger mb-4">Tidak Ada</p>
                    @endif
                </div>
                <div class="divider"></div>
                <div class="logo-section text-center">
                    <div class="logo-photo" style="position: relative;">
                        <a href="javascript:;"
                            onclick="event.preventDefault();document.getElementById('ownerLogoImageFile').click();"
                            class="edit-avatar"
                            style="position: absolute; top: 0; right: 0; background-color: #757575; border-radius: 50%; padding: 5px; transition: background-color 0.3s, color 0.3s;"
                            onmouseover="this.style.backgroundColor='white'; this.querySelector('i').style.color='#757575';"
                            onmouseout="this.style.backgroundColor='#757575'; this.querySelector('i').style.color='white';"><i
                                class="fas fa-pencil-alt"
                                style="color: white; font-size: 20px; width: 30px; height: 30px; display: inline-flex; justify-content: center; align-items: center;"></i></a>
                        <img src="{{ $owner->logo }}" alt="" class="avatar-photo" id="ownerLogoImage">
                        <input type="file" name="ownerLogoImageFile" id="ownerLogoImageFile" class="d-none"
                            style="opacity:0">
                    </div>
                    <h5 class="text-center h5 my-2">Logo Studio Foto</h5>
                    @if ($owner->logo !== 'http://studiofoto.test/images/users/owners/LOGO_owner/default-logo.png')
                        <p class="alert alert-success mb-4">Ada</p>
                    @else
                        <p class="alert alert-danger mb-4">Tidak Ada</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a wire:click.prevent='selectTab("personal_details")'
                                    class="nav-link {{ $tab == 'personal_details' ? 'active' : '' }}" data-toggle="tab"
                                    href="#personal_details" role="tab">Personal Details</a>
                            </li>
                            <li class="nav-item">
                                <a wire:click.prevent='selectTab("update_password")'
                                    class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab"
                                    href="#update_password" role="tab">Update Password</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
                            <div class="tab-pane fade {{ $tab == 'personal_details' ? 'active show' : '' }}"
                                id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit.prevent='updateOwnerPersonalDetails'>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Name</label>
                                                    <input type='text' class="form-control" wire:model='name'
                                                        placeholder='Enter full name'>
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type='text' class="form-control" wire:model='email'
                                                        placeholder='Enter Email' disabled>
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Username</label>
                                                    <input type='text' class="form-control" wire:model='username'
                                                        placeholder='Enter Username'>
                                                    @error('username')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Phone Number</label>
                                                    <input type='text' class="form-control" wire:model='handphone'
                                                        placeholder='Enter Your Phone Number...'>
                                                    @error('handphone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Address</label>
                                                    <input type='text' class="form-control" wire:model='address'
                                                        placeholder='Enter Your Address...'>
                                                    @error('address')
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
                            <div class="tab-pane fade {{ $tab == 'update_password' ? 'active show' : '' }}"
                                id="update_password" role="tabpanel">
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
                                                        wire:model.defer='new_password_confirmation'
                                                        class="form-control">
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
                </div>
            </div>
        </div>
    </div>
</div>
