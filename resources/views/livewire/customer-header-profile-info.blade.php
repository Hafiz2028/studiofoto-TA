<div class="header__top__right__language">
    <div style="position: relative; display: inline-block;">
        <div style="display: flex; align-items: center;">
            <a href="#" style="text-decoration: none; color: #333;" onclick="toggleDropdown()">
                <img src="{{ auth()->guard('customer')->user()->picture }}" alt="User Picture"
                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; margin-right: 5px;">
                <span>{{ auth()->guard('customer')->user()->name }}</span>
                <span class="arrow_carrot-down" style="margin-left: 5px;"></span>
            </a>
        </div>
        <ul id="dropdown-menu"
            style="z-index:9;position: absolute;width:100px;text-align:left;padding: 5px 0;left: 0;top: 43px; background-color: #fff;border: 1px solid #ccc;border-radius: 4px;display: none;">
            <li><a href="{{ route('customer.profile') }}" style="text-decoration: none; color: #333;">Profile</a></li>
            <li>
                <form method="post" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="logout-button"
                        style="background: none; border: none; padding: 5px 10px; font: inherit; cursor: pointer; color: #333; font-size: normal; text-decoration: none;">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>
