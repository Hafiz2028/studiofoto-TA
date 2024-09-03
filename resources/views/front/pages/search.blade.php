@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form" style="width: 100%">
                            <form id="searchForm" action="{{ url()->current() }}" method="GET">
                                <input type="text" id="searchInput"name="search" value="{{ request('search') }}"
                                    placeholder="Find Photo Studio...">
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
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/front/img/tomat.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Search Venue</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Search</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar__item">
                        <h4>Lokasi Studio Terdaftar</h4>
                        <ul>
                            <li class="{{ empty(request('district_id')) && empty(request('village_id')) ? 'active' : '' }}">
                                <a href="{{ url()->current() }}#venueSection">Semua Studio Foto
                                    ({{ $totalVenuesCount }})</a>
                            </li>
                            @foreach ($districts as $districtName => $villages)
                                @php
                                    $districtId = $villages->first()->district_id; // Ambil district_id dari salah satu village
                                    $districtName = $villages->first()->district->name;
                                    $activeDistrict = request('district_id') == $districtId;
                                    $activeVillage = false;
                                @endphp
                                <li class="has-submenu {{ $activeDistrict ? 'active' : '' }}">
                                    <a href="{{ url()->current() }}?district_id={{ $districtId }}#venueSection">
                                        {{ ucwords(strtolower($districtName)) }}
                                        ({{ $districtVenuesCount[$districtName] ?? 0 }})
                                    </a>
                                    @if ($villages->isNotEmpty())
                                        <ul class="submenu">
                                            @foreach ($villages as $village)
                                                @if (($villageVenuesCount[$village->id] ?? 0) > 0)
                                                    {{-- Hanya tampilkan jika jumlah venue > 0 --}}
                                                    <li
                                                        class="{{ $activeVillage && request('village_id') == $village->id ? 'active' : '' }}">
                                                        <a
                                                            href="{{ url()->current() }}?village_id={{ $village->id }}#venueSection">
                                                            {{ ucwords(strtolower($village->name)) }}
                                                            ({{ $villageVenuesCount[$village->id] ?? 0 }})
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <div id="sortBy">
                                        <span>Sort By</span>
                                        <i class="icon-copy bi bi-sort-alpha-down ml-2 {{ $sort === 'name_asc' ? 'active' : '' }}"
                                            id="sortNameAsc" data-sort="name_asc" data-toggle="tooltip"
                                            title="Nama Venue A-Z"></i>
                                        <i class="icon-copy bi bi-sort-alpha-down-alt ml-2 {{ $sort === 'name_desc' ? 'active' : '' }}"
                                            id="sortNameDesc" data-sort="name_desc" data-toggle="tooltip"
                                            title="Nama Venue Z-A"></i>
                                        <i class="icon-copy bi bi-sort-numeric-down ml-2 {{ $sort === 'price_asc' ? 'active' : '' }}"
                                            id="sortPriceAsc" data-sort="price_asc" data-toggle="tooltip"
                                            title="Start Harga Termurah"></i>
                                        <i class="icon-copy bi bi-sort-numeric-down-alt ml-2 {{ $sort === 'price_desc' ? 'active' : '' }}"
                                            id="sortPriceDesc" data-sort="price_desc" data-toggle="tooltip"
                                            title="Start Harga Termahal"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div id="venueSection" class="filter__found">
                                    <h6><span>{{ $venues->total() }}</span> Venue ditemukan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="productContainer">
                        <div class="row">
                            @if ($venues->isEmpty())
                                <div class="col-lg-12">
                                    <div class="alert alert-warning text-center" role="alert">
                                        Tidak ada studio foto yang tersedia di lokasi ini.
                                    </div>
                                </div>
                            @else
                                @foreach ($venues as $venue)
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item" data-name="{{ $venue->name }}">
                                            <div class="product__item__pic set-bg"
                                                @if ($venue->venueImages->isNotEmpty()) data-setbg="/images/venues/Venue_Image/{{ $venue->venueImages->first()->image }}"
                                alt="{{ $venue->venueImages->first()->image }}"
                                style="background-image: url('/images/venues/Venue_Image/{{ $venue->venueImages->first()->image }}');"
                                @else
                                    data-setbg="/images/venues/Venue_Image/default-venue.png"
                                    alt="Tidak Ada Gambar Venue" @endif>
                                                <ul class="product__item__pic__hover">
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
                                                        @if (auth()->check() && auth()->user()->role === 'customer')
                                                            <a href="{{ route('customer.detail-venue', $venue->id) }}">
                                                                <i class="fas fa-info" data-toogle="tooltip"
                                                                    title="Detail Studio Foto"
                                                                    data-placement="auto"></i></a>
                                                        @else
                                                            <a
                                                                href="{{ route('customer.detail-venue-not-login', $venue->id) }}">
                                                                <i class="fas fa-info" data-toogle="tooltip"
                                                                    title="Detail Studio Foto"
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
                                                            target="_blank" data-toogle="tooltip"
                                                            title="Chat pihak Studio Foto" data-placement="auto">
                                                            <i class="fab fa-whatsapp" style="font-size:6mm;"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product__item__text">
                                                @if (auth()->check() && auth()->user()->role === 'customer')
                                                    <h6><a
                                                            href="{{ route('customer.detail-venue', $venue->id) }}">{{ $venue->name }}</a>
                                                    </h6>
                                                @else
                                                    <h6><a
                                                            href="{{ route('customer.detail-venue-not-login', $venue->id) }}">{{ $venue->name }}</a>
                                                    </h6>
                                                @endif
                                                <h5
                                                    style="font-size: 16px; color: #333; margin-top: 10px; text-align:left;">
                                                    <span
                                                        style="display: inline-block; font-size: 12px; vertical-align: super; font-weight: normal;">Start
                                                        from</span> Rp.
                                                    {{ number_format($venue->min_price ?? 0, 2, ',', '.') }}
                                                </h5>
                                                {{-- <h6 style="text-align: left;"> {{$phone_number}}</h6> --}}
                                                <p class="mt-2" style="text-align: left;">
                                                    {{ ucwords(strtolower($venue->address)) }},
                                                    {{ ucwords(strtolower($venue->village->name)) }},
                                                    {{ ucwords(strtolower($venue->village->district->name)) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="product__pagination">
                        @if ($venues->lastPage() > 1)
                            @for ($i = 1; $i <= $venues->lastPage(); $i++)
                                <a href="{{ $venues->url($i) }}"
                                    class="{{ $venues->currentPage() == $i ? 'active' : '' }}">{{ $i }}</a>
                            @endfor
                            @if ($venues->hasMorePages())
                                <a href="{{ $venues->nextPageUrl() }}"><i class="fas fa-long-arrow-alt-right"></i></a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

@endsection
@push('stylesheets')
    <style>
        .icon-copy {
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .icon-copy:hover {
            color: #e27201;
        }
    </style>
    {{-- sidebar --}}
    <style>
        /* Menyembunyikan submenu secara default */
        .sidebar__item .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            opacity: 0;
            margin-left: 20px;
            list-style-type: disc;
        }

        /* Menampilkan submenu saat item induk di-hover dengan animasi smooth */
        .sidebar__item .has-submenu:hover .submenu {
            max-height: 200px;
            /* Set max-height to a value large enough to show the submenu */
            opacity: 1;
        }

        /* Styling untuk link submenu */
        .sidebar__item .submenu li a {
            font-size: 0.9em;
            color: #555;
        }

        .sidebar__item .submenu li a:hover {
            color: #e27201;
            /* Warna saat dihover */
        }
    </style>
    {{-- sidebar --}}
    <style>
        .sidebar__item ul {
            border-radius: 10px;
            overflow: hidden;
        }

        .sidebar__item ul li {
            border-radius: 10px;
        }

        .sidebar__item ul li.active>a {
            background-color: #e27201;
            color: #fff;
        }

        .sidebar__item ul li.has-submenu:hover>a {
            background-color: #f0f0f0;
            color: #000;
        }

        .sidebar__item ul li.has-submenu ul.submenu {
            display: none;
            padding-left: 15px;
            background-color: #fff;
        }

        .sidebar__item ul li.has-submenu:hover ul.submenu {
            display: block;
        }

        .sidebar__item ul li.has-submenu.active>a {
            color: #fff;
            /* Teks tetap putih saat village aktif */
        }

        .sidebar__item ul li.has-submenu.active:hover>a {
            background-color: #e27201;
            /* Latar belakang kembali ke warna utama saat dihover */
            color: #fff;
            /* Teks tetap putih saat dihover */
        }

        .sidebar__item ul li.has-submenu.active ul.submenu li.active>a {
            background-color: #e27201;
            /* Latar belakang village aktif saat dihover */
            color: #fff;
            /* Teks tetap putih saat village aktif dihover */
        }
    </style>
    <style>
        #sortBy {
            display: flex;
            align-items: center;
        }

        #sortBy .icon-copy {
            font-size: 1.2rem;
            margin-right: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #sortBy .icon-copy.active {
            background-color: #e27201;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        #sortBy .icon-copy.active:hover {
            background-color: #e27201;
            /* Biarkan warna sama dengan saat aktif */
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(function() {
            $("#searchInput").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ url('/api/venue-suggestions') }}",
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sortIcons = document.querySelectorAll('#sortBy i');

            sortIcons.forEach(function(icon) {
                icon.addEventListener('click', function() {
                    var sortValue = this.getAttribute('data-sort');
                    sort(sortValue);
                });
            });

            function sort(sortValue) {
                var url = new URL(window.location.href);
                url.searchParams.set('sort', sortValue);
                url.hash = '#venueSection';
                window.location.href = url.href;
            }
            if (window.location.hash === '#venueSection') {
                document.querySelector(window.location.hash).scrollIntoView();
            }
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
@endpush
