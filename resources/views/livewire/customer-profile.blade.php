<div>

    <div class="card">
        <div class="card-body px-4 m-4">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                    <div class="pd-20 card-box height-100-p">
                        <div class="profile-section">
                            <div class="profile-photo text-center position-relative">
                                <img src="{{ $user->picture }}" alt="" class="avatar-photo rounded-circle""
                                    id="customerProfilePicture">
                                <a href="javascript:;"
                                    onclick="event.preventDefault();document.getElementById('customerProfilePictureFile').click();"
                                    class="edit-avatar position-absolute top-0 end-0 m-2"><i class="fas fa-pencil-alt"></i></a>
                                <input type="file" name="customerProfilePictureFile" id="customerProfilePictureFile"
                                    class="d-none" style="opacity:0">
                            </div>
                            <h5 class="text-center h5 mb-0 mt-3" id="customerProfileName">{{ $user->name }}</h5>
                            <p class="text-center text-muted font-14" id="customerProfileEmail">{{ $user->email }}
                            </p>
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
                                            class="nav-link {{ $tab == 'personal_details' ? 'active' : '' }}"
                                            data-toggle="tab" href="#personal_details" role="tab">Personal
                                            Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:click.prevent='selectTab("update_password")'
                                            class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}"
                                            data-toggle="tab" href="#update_password" role="tab">Update Password</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Timeline Tab start -->
                                    <div class="tab-pane fade {{ $tab == 'personal_details' ? 'active show' : '' }}"
                                        id="personal_details" role="tabpanel">
                                        <div class="pd-20">
                                            <form wire:submit.prevent='updateCustomerPersonalDetails'>
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
                                                            <input type='text' class="form-control"
                                                                wire:model='email' placeholder='Enter Email' disabled>
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
                                                            <input type='text' class="form-control"
                                                                wire:model='username' placeholder='Enter Username'>
                                                            @error('username')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Phone Number</label>
                                                            <input type='text' class="form-control"
                                                                wire:model='handphone'
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
                                                            <input type='text' class="form-control"
                                                                wire:model='address'
                                                                placeholder='Enter Your Address...'>
                                                            @error('address')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-success">Save Changes</button>
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
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="">Current Password</label>
                                                            <input type="password"
                                                                placeholder="Enter current password"
                                                                wire:model.defer='current_password'
                                                                class="form-control">
                                                            @error('current_password')
                                                                <span class="text-danger">{{ $message }} </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="">New Password</label>
                                                            <input type="password" placeholder="Enter new password"
                                                                wire:model.defer='new_password'class="form-control">
                                                            @error('new_password')
                                                                <span class="text-danger">{{ $message }} </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
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
                                                <button type="submit" class="btn btn-success">Update
                                                    Password</button>
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
    </div>
</div>
