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
                                <li data-filter=".{{ $service_type->service_slug }}"><a
                                        href="#featured-section">{{ $service_type->service_name }}</a></li>
                            @endforeach
                        @else
                            <li><a class="text-danger" href="#">No Service Event</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                {{-- <div class="hero__search">
                    <div class="hero__search__form" style="width: 100%">
                        <form action="#">
                            <input type="text" placeholder="Find Photo Studio...">
                            <button type="submit" class="site-btn">SEARCH</button>
                        </form>
                    </div>
                </div> --}}
                <!--first-banner begin-->
                @includeWhen(Request::is('/'), 'front.layout.inc.first-banner')
                <!--first-banner end-->
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var categoryLinks = document.querySelectorAll('.hero__categories ul li a');

        categoryLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var filter = this.parentElement.getAttribute('data-filter');

                var section = document.querySelector('#featured-section');
                window.scrollTo({
                    top: section.offsetTop,
                    behavior: 'smooth'
                });

                var featuredControls = document.querySelectorAll('.featured__controls ul li');
                featuredControls.forEach(function(control) {
                    control.classList.remove('active');
                    if (control.getAttribute('data-filter') === filter) {
                        control.classList.add('active');
                    }
                });
                var event = new CustomEvent('filterItems', {
                    detail: filter
                });
                document.dispatchEvent(event);
            });
        });
    });
    document.addEventListener('filterItems', function(event) {
        var filterValue = event.detail;
        $('.featured__controls').isotope({
            filter: filterValue
        });
    });
</script>
