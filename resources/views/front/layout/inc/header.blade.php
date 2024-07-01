<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__left">
                        <ul>
                            <li><i class="fa fa-envelope"></i> fotoyuk@example.com</li>
                            <li>Ayo cari Studio Foto di FOTOYUK</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">
                            <a href="https://www.instagram.com/apis_a_r" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                        </div>
                        @if (auth()->guard('customer')->check())
                            @livewire('customer-header-profile-info')
                        @else
                            <div class="header__top__right__language">
                                <a href="#"><i class="fa fa-user"></i> Login as</a>
                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="{{ route('owner.login') }}">Owner</a></li>
                                    <li><a href="{{ route('customer.login') }}">Customer</a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="header__logo">
                    <a href="{{ route('home') }}"><img src="/front/img/logo_name.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-7">
                <nav class="header__menu">
                    <ul>
                        <li class="{{ Route::is('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                        <li class="{{ Route::is('search-page') ? 'active' : '' }}"><a
                                href="{{ route('search-page') }}">Search</a></li>
                        @if (auth()->guard('customer')->check())
                            <li class="{{ Route::is('customer.booking.index') ? 'active' : '' }}"><a
                                    href="{{ route('customer.booking.index') }}">Booking</a>
                                {{-- <ul class="header__menu__dropdown">
                                    <li><a href="./shop-details.html">Shop Details</a></li>
                                    <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                                    <li><a href="./checkout.html">Check Out</a></li>
                                    <li><a href="./blog-details.html">Blog Details</a></li>
                                </ul> --}}
                            </li>
                            <li class="{{ Route::is('customer.history.index') ? 'active' : '' }}"><a
                                    href="{{ route('customer.history.index') }}">History</a></li>
                            <li class="{{ Route::is('customer.profile') ? 'active' : '' }}"><a
                                    href="{{ route('customer.profile') }}">Profile</a></li>
                        @endif
                    </ul>
                </nav>
            </div>
            {{-- <div class="col-lg-2">
                <div class="header__cart">
                    <ul>
                        <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                        <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
                    </ul>
                    <div class="header__cart__price">item: <span>$150.00</span></div>
                </div>
            </div> --}}
        </div>
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<script>
    function toggleDropdown() {
        var dropdownMenu = document.getElementById("dropdown-menu");
        dropdownMenu.style.display = dropdownMenu.style.display === "none" ? "block" : "none";
    }
</script>
