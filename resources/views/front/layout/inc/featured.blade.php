<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Featured Product</h2>
                </div>
                <div class="featured__controls">
                    {{-- <ul>
                        <li class="active" data-filter="*">All</li>
                        <li data-filter=".oranges">Oranges</li>
                        <li data-filter=".fresh-meat">Fresh Meat</li>
                        <li data-filter=".vegetables">Vegetables</li>
                        <li data-filter=".fastfood">Fastfood</li>
                    </ul> --}}

                    <ul>
                        <li class="active" data-filter="*">All</li>
                        @if (count(get_service_types()) > 0)
                            @foreach (get_service_types() as $service_type)
                                <li data-filter=".{{ $service_type->service_slug }}">{{ $service_type->service_name }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            {{-- <div class="col-lg-3 col-md-4 col-sm-6 mix oranges fresh-meat">
                <div class="featured__item">
                    <div class="featured__item__pic set-bg" data-setbg="/front/img/featured/feature-1.jpg">
                        <ul class="featured__item__pic__hover">
                            <li><a href="#"><i class="fa fa-heart"></i></a></li>
                            <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                    <div class="featured__item__text">
                        <h6><a href="#">Crab Pool Security</a></h6>
                        <h5>$30.00</h5>
                    </div>
                </div>
            </div> --}}
            @if (count(get_venues_with_service_slug()) > 0)
                @foreach (get_venues_with_service_slug() as $venue)
                <div class="col-lg-3 col-md-4 col-sm-6 mix @foreach ($venue->serviceEvents as $serviceEvent){{ $serviceEvent->serviceType->service_slug }} @endforeach">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="/images/venues/Venue_Image/{{ $venue->venueImages->first()->image }}">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart" data-toogle="tooltip" title="Favorite"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a href="#">{{ $venue->name}}</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

        </div>
    </div>
</section>
