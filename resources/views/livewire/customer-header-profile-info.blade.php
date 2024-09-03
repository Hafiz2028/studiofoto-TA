    <div class="header__top__right__language" style="position: relative; z-index: 10000;">
        <div style="position: relative; display: inline-block;">
            <div style="display: flex; align-items: center;">
                <a href="#" style="text-decoration: none; color: #333;" onclick="toggleDropdown(event)">
                    <img src="{{ auth()->user()->picture }}" alt="User Picture"
                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; margin-right: 5px;">
                    <span>{{ auth()->user()->name }}</span>
                    <span class="arrow_carrot-down" style="margin-left: 5px;"></span>
                </a>
            </div>
            <ul id="dropdown-menu"
                style="z-index: 10001; position: absolute; width: 150px; text-align: left; padding: 5px 0; left: 0; top: 43px; background-color: rgb(255, 255, 255); border: 1px solid #000; border-radius: 4px; display: none;">
                <li><a href="{{ route('customer.profile') }}" style="text-decoration: none; color: #333;">Profile</a>
                </li>
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
