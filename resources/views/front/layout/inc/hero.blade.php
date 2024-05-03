<section class="{{ $class }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Service Event Type</span>
                    </div>
                    <ul>
                        @if (count(get_service_types()) > 0)
                            @foreach (get_service_types() as $service_type)
                                <li><a href="#">{{ $service_type->service_name }}</a></li>
                            @endforeach
                        @else
                            <li><a class="text-danger" href="#">No Service Event</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form" style="width: 100%">
                        <form action="#">
                            {{-- <div class="hero__search__categories">
                                All Categories
                                <span class="arrow_carrot-down"></span>
                            </div> --}}
                            <input type="text" placeholder="What do yo u need?">
                            <button type="submit" class="site-btn">SEARCH</button>
                        </form>
                    </div>
                </div>
                <!--first-banner begin-->
                @includeWhen(Request::is('/'), 'front.layout.inc.first-banner')
                <!--first-banner end-->
            </div>
        </div>
    </div>
</section>
