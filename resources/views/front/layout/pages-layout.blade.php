<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('pageTitle')</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="/front/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="/front/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="/front/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/style.css" type="text/css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css">

    @livewireStyles()
    @stack('stylesheets')
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Humberger Begin -->
    @include('front.layout.inc.humberger')
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    @include('front.layout.inc.header')
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    @if (Request::is('/'))
        @include('front.layout.inc.hero', ['class' => 'hero'])
    @else
        @include('front.layout.inc.hero', ['class' => 'hero hero-normal'])
    @endif
    <!-- Hero Section End -->

    @yield('content')


    <!-- Categories Section Begin -->
    <!-- Categories Section End -->

    <!-- Featured Section Begin -->
    <!-- Featured Section End -->

    <!-- Banner Begin -->
    <!-- Banner End -->

    <!-- Latest Product Section Begin -->
    <!-- Latest Product Section End -->

    <!-- Blog Section Begin -->
    <!-- Blog Section End -->

    <!-- Footer Section Begin -->
    @include('front.layout.inc.footer')
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="/front/js/jquery-3.3.1.min.js"></script>
    <script src="/front/js/bootstrap.min.js"></script>
    <script src="/front/js/jquery.nice-select.min.js"></script>
    <script src="/front/js/jquery-ui.min.js"></script>
    <script src="/front/js/jquery.slicknav.js"></script>
    <script src="/front/js/mixitup.min.js"></script>
    <script src="/front/js/owl.carousel.min.js"></script>
    <script src="/front/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>



    @livewireScripts()
    @stack('scripts')



</body>

</html>
