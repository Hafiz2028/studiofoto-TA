<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Service Event Type</h2>
                </div>
                <div class="featured__controls">
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
                    <div
                        class="col-lg-3 col-md-4 col-sm-6 mix @if (!empty($venue->service_slugs)) @foreach ($venue->service_slugs as $service_slug){{ $service_slug }} @endforeach @endif">
                        <div class="featured__item">
                            <div class="featured__item__pic set-bg"
                                @if ($venue->venueImages->isNotEmpty()) data-setbg="/images/venues/Venue_Image/{{ $venue->venueImages->first()->image }}"
                                    alt="{{ $venue->venueImages->first()->image }}"
                                    style="background-image: url('/images/venues/Venue_Image/{{ $venue->venueImages->first()->image }}');"
                                @else
                                    data-setbg="/images/venues/Venue_Image/default-venue.png"
                                    alt="Tidak Ada Gambar Venue" @endif>
                                <ul class="featured__item__pic__hover">
                                    <li>
                                        @if (filter_var($venue->map_link, FILTER_VALIDATE_URL))
                                            <a href="{{ $venue->map_link }}" target="_blank"
                                                onclick="return openLink(event, '{{ $venue->map_link }}')">
                                                <i class="fa fa-map-marker" data-toggle="tooltip"
                                                    title="Lihat Lokasi di Maps"></i>
                                            </a>
                                        @else
                                            <a href="#" onclick="alert('Link tidak valid'); return false;">
                                                <i class="far fa-map-marker" data-toggle="tooltip"
                                                    title="Lihat Lokasi di Maps"></i>
                                            </a>
                                        @endif
                                    </li>
                                    @if (auth()->guard('customer')->check())
                                        <li><a href="{{ route('customer.detail-venue', $venue->id) }}">
                                                <i class="fas fa-info" data-toogle="tooltip" title="Detail Studio Foto"
                                                    data-placement="auto"></i></a>
                                        @else
                                        <li><a id="openDetailVenue" data-toogle="tooltip" title="Detail Studio Foto"><i
                                                    class="fa fa-info"></i></a>
                                    @endif
                                    </li>
                                    <li>
                                        <a href="https://wa.me/{{ $venue->phone_number }}?text={{ urlencode('Halo, saya ingin booking jadwal studio foto.') }}"
                                            target="_blank" data-toogle="tooltip" title="Chat pihak Studio Foto"
                                            data-placement="auto">
                                            <i class="fab fa-whatsapp" style="font-size:6mm;"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="featured__item__text">
                                @if (auth()->guard('customer')->check())
                                    <h6><a
                                            href="{{ route('customer.detail-venue', $venue->id) }}">{{ $venue->name }}</a>
                                    </h6>
                                @else
                                    <h6><a id="openDetailVenue">{{ $venue->name }}</a>
                                    </h6>
                                @endif
                                <h5 style="font-size: 16px; color: #333; margin-top: 10px;">
                                    <span
                                        style="display: inline-block; font-size: 12px; vertical-align: super; font-weight: normal;">Start
                                        from</span> Rp. {{ number_format($venue->min_price ?? 0, 2, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openDetailVenue = document.getElementById('openDetailVenue');

        openDetailVenue.addEventListener('click', function() {
            Swal.fire({
                title: 'Belum Login',
                text: 'Silahkan untuk melakukan login atau register akun.',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Login',
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Register',
                cancelButtonColor: '#2843da',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('customer.login') }}";
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = "{{ route('customer.register') }}";
                }
            });
        });
    });
</script>
<script>
    function openLink(event, url) {
        event.preventDefault();
        window.open(url, '_blank');
        return false;
    }
</script>
