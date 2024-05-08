<section class="categories">
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="section-title product__discount__title">
                    <h2>Sale Off</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="categories__slider owl-carousel">
                @if (count(get_service_types()) > 0)
                    @foreach (get_service_types() as $service_type)
                        <div class="col-lg-3">
                            <div class="product__discount__item">
                                <div class="product__discount__item__pic set-bg"
                                    data-setbg="/front/img/product/discount/pd-1.jpg">
                                    <div class="product__discount__percent">-20%</div>
                                    <ul class="product__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__discount__item__text">
                                    <span>{{ $service_type->service_name }} service event</span>
                                    <h5><a href="#">nama venue </a></h5>
                                    <div class="product__item__price">$30.00 <span>$36.00</span></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-lg-3">
                        <h3><a class="alert alert-danger" href="#">No Service Event</a></h3>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
