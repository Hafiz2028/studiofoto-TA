<section id="featured-section" class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Tipe Layanan</h2>
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
                                                <i class="icon-copy fa fa-map-marker" data-toggle="tooltip"
                                                    title="Lihat Lokasi di Maps" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <a href="#" onclick="swalAlert(); return false;">
                                                <i class="icon-copy fa fa-map-marker" data-toggle="tooltip"
                                                    title="Lihat Lokasi di Maps" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </li>
                                    <li>
                                        @if (auth()->guard('customer')->check())
                                            <a href="{{ route('customer.detail-venue', $venue->id) }}">
                                                <i class="fas fa-info" data-toogle="tooltip" title="Detail Studio Foto"
                                                    data-placement="auto"></i></a>
                                        @else
                                            <a href="{{ route('customer.detail-venue-not-login', $venue->id) }}">
                                                <i class="fas fa-info" data-toogle="tooltip" title="Detail Studio Foto"
                                                    data-placement="auto"></i></a>
                                        @endif
                                    </li>
                                    <li>
                                        @php
                                            $phone_number = $venue->phone_number;
                                            if (substr($phone_number, 0, 2) == '08') {
                                                $phone_number = '628' . substr($phone_number, 2);
                                            }
                                        @endphp
                                        <a href="https://wa.me/{{ $phone_number }}?text={{ urlencode('Halo, saya ingin booking jadwal studio foto.') }}"
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
                                    <h6><a
                                            href="{{ route('customer.detail-venue-not-login', $venue->id) }}">{{ $venue->name }}</a>
                                    </h6>
                                @endif
                                <h5 style="font-size: 16px; color: #333; margin-top: 10px; text-align:left;">
                                    <span
                                        style="display: inline-block; font-size: 12px; vertical-align: super; font-weight: normal;">Start
                                        from</span> Rp. {{ number_format($venue->min_price ?? 0, 2, ',', '.') }}
                                </h5>
                                {{-- <h6 style="text-align: left;"> {{$phone_number}}</h6> --}}
                                <p class="mt-2" style="text-align: left;">
                                    {{ ucwords(strtolower($venue->address)) }},
                                    {{ ucwords(strtolower($venue->village->district->name)) }},
                                    {{ ucwords(strtolower($venue->village->name)) }},
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script>
    function swalAlert() {
        Swal.fire({
            icon: 'warning',
            title: 'Link tidak valid',
            text: 'Link Lokasi dari Studio Foto tidak Valid',
            showConfirmButton: true
        });
    }
</script>
