<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="{{ route('home') }}"><img src="/front/img/logo_name.png" alt=""></a>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__language">
            @if (auth()->check() && auth()->user()->role === 'customer')
                @livewire('customer-header-profile-info')
            @else
                <a href="#"><i class="fa fa-user"></i> Login as</a>
                <span class="arrow_carrot-down"></span>
                <ul>
                    <li><a href="{{ route('owner.login') }}">Owner</a></li>
                    <li><a href="{{ route('customer.login') }}">Customer</a></li>
                </ul>
            @endif
        </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li class="{{ Route::is('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
            <li class="{{ Route::is('search-page') ? 'active' : '' }}"><a href="{{ route('search-page') }}">Search</a>
            </li>
            @if (auth()->check() && auth()->user()->role === 'customer')
                <li class="{{ Route::is('customer.booking.index') ? 'active' : '' }}"><a
                        href="{{ route('customer.booking.index') }}">Booking</a>
                </li>
                <li class="{{ Route::is('customer.history.index') ? 'active' : '' }}"><a
                        href="{{ route('customer.history.index') }}">History</a></li>
                <li class="{{ Route::is('customer.profile') ? 'active' : '' }}"><a
                        href="{{ route('customer.profile') }}">Profile</a></li>
            @endif
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">
        <a href="https://www.instagram.com/apis_a_r" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin"></i></a>
        <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
    </div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> fotoyuk@studio.com</li>
            <li>Ayo cari Studio Foto di FOTOYUK</li>
        </ul>
    </div>
</div>

<style>
    .header__top__right__language {
        position: relative;
        z-index: 9999 !important;
        /* Ensure it's on top */
    }

    #dropdown-menu {
        z-index: 10000 !important;
        display: block !important;
        /* Force display for debugging */
    }

    .humberger__menu__overlay {
        z-index: 1;
        /* Ensure it's below the dropdown */
    }

    .humberger__menu__wrapper {
        z-index: 2;
        /* Ensure it's below the dropdown */
    }
</style>

<script>
    function toggleDropdown(event) {
        event.preventDefault();
        event.stopPropagation();
        console.log("Dropdown toggle clicked");

        var dropdownMenu = document.getElementById("dropdown-menu");
        if (dropdownMenu) {
            var isVisible = dropdownMenu.style.display === "block";
            console.log("Dropdown current visibility:", dropdownMenu.style.display);
            closeAllDropdowns();
            if (!isVisible) {
                dropdownMenu.style.display = "block";
                console.log("Dropdown set to block");
            }
        } else {
            console.error("Dropdown menu not found!");
        }
    }

    function closeAllDropdowns() {
        var dropdowns = document.querySelectorAll("#dropdown-menu");
        dropdowns.forEach(function(dropdown) {
            dropdown.style.display = "none";
        });
    }

    document.addEventListener("click", function(event) {
        if (!event.target.closest('.header__top__right__language')) {
            closeAllDropdowns();
        }
    });

    document.getElementById("dropdown-menu").addEventListener("click", function(event) {
        event.stopPropagation();
    });
</script>
