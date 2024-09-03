<div>

    @if (auth()->check() && auth()->user()->role === 'admin')
        <div class="user-info-dropdown"> 
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{ $user->picture }}" alt="" />
                    </span>
                    <span class="user-name">{{ $user->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="dw dw-user1"></i> Profile</a>
                    {{-- <a class="dropdown-item" href=""
								><i class="dw dw-settings2"></i> Setting</a
							>
							<a class="dropdown-item" href="faq.html"
								><i class="dw dw-help"></i> Help</a
							> --}}
                    <a class="dropdown-item" href="{{ route('admin.logout_handler') }}"
                        onclick="event.preventDefault();document.getElementById('adminLogoutForm').submit
                                ();"><i
                            class="dw dw-logout"></i> Log Out</a>
                    <form action="{{ route('admin.logout_handler') }}" id="adminLogoutForm" method="POST">@csrf</form>
                </div>
            </div>
        </div>
    @elseif(auth()->check() && auth()->user()->role === 'owner')
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{ $user->picture }}" alt="" />
                    </span>
                    <span class="user-name">{{ $user->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="{{ route('owner.profile') }}"><i class="dw dw-user1"></i> Profile</a>
                    <a class="dropdown-item" href="{{ route('owner.logout') }}"
                        onclick="event.preventDefault();document.getElementById('ownerLogoutForm').submit
                                ();"><i
                            class="dw dw-logout"></i> Log Out</a>
                    <form action="{{ route('owner.logout') }}" id="ownerLogoutForm" method="POST">@csrf</form>
                </div>
            </div>
        </div>
    @endif


</div>
