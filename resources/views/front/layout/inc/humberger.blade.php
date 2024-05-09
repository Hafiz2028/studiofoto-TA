<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="#"><img src="/front/img/logo.png" alt=""></a>
    </div>
    <div class="humberger__menu__cart">
        <ul>
            <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
            <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
        </ul>
        <div class="header__cart__price">item: <span>$150.00</span></div>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__language">
            @if (auth()->guard('customer')->check())
                <a href="#"><i class="fa fa-user"></i> Details</a>
                <span class="arrow_carrot-down"></span>
                <ul>
                    <li><a href="{{ route('customer.profile') }}">Profile</a></li>
                    <li>
                        <form method="post" action="{{ route('customer.logout') }}">
                            @csrf
                            <button type="submit"
                                style="background: none; border: none; padding: 5px 10px; font: inherit; cursor: pointer; color: white; text-decoration: none; font-size: smaller;">Logout</button>
                        </form>
                    </li>
                </ul>
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
            @if (auth()->guard('customer')->check())
                <li><a href="#">Booking</a>
                    <ul class="header__menu__dropdown">
                        <li><a href="./shop-details.html">Shop Details</a></li>
                        <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                        <li><a href="./checkout.html">Check Out</a></li>
                        <li><a href="./blog-details.html">Blog Details</a></li>
                    </ul>
                </li>
                <li><a href="./blog.html">History</a></li>
                <li><a href="./contact.html">Chat</a></li>
                <li><a href="{{route('customer.profile')}}">Profile</a></li>
            @endif
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-linkedin"></i></a>
        <a href="#"><i class="fa fa-pinterest-p"></i></a>
    </div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> fotoyuk@studio.com</li>
            <li>Ayo cari Studio Foto di FOTOYUK</li>
        </ul>
    </div>
</div>
