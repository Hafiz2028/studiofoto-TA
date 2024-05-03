<section class="categories">
    <div class="container">
        <div class="row">
            <div class="categories__slider owl-carousel">

                @if (count(get_service_types()) > 0)
                    @foreach (get_service_types() as $service_type)
                        <div class="col-lg-3">
                            <div class="categories__item set-bg" data-setbg="/front/img/categories/cat-1.jpg">
                                <h5><a href="#">{{ $service_type->service_name }}</a></h5>
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
