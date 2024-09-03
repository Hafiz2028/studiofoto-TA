<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Foto Yuk Landing Page">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('pageTitle')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/back/vendors/images/favicon-180x180.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/back/vendors/images/favicon-32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/back/vendors/images/favicon-16.png" />
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <style>
        .badge {
            font-family: 'Inter', sans-serif !important;
            font-size: 12px !important;
            padding: 8px !important;
        }
    </style>
    <!-- Css Styles -->
    <link rel="stylesheet" href="/front/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/elegant-icons.css" type="text/css">
    {{-- <link rel="stylesheet" href="/front/css/nice-select.css" type="text/css"> --}}
    <link rel="stylesheet" href="/front/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="/front/css/style.css" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css">

    <!-- CSS backend -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/jquery-steps/jquery.steps.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/switchery/switchery.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.2/jquery-ui.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.2/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.2/jquery-ui.theme.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    {{-- tambahan --}}
    <!-- Lightbox2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    @livewireStyles
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
    @if (Request::is('/') || Request::is('/customer'))
        @include('front.layout.inc.hero', ['class' => 'hero'])
    @else
        {{-- @include('front.layout.inc.hero', ['class' => 'hero hero-normal']) --}}
    @endif
    <!-- Hero Section End -->

    @yield('content')

    <!-- Footer Section Begin -->
    @include('front.layout.inc.footer')
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="/front/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="/front/js/bootstrap.min.js"></script>
    {{-- <script src="/front/js/jquery.nice-select.min.js"></script> --}}
    <script src="/front/js/jquery-ui.min.js"></script>
    <script src="/front/js/jquery.slicknav.js"></script>
    <script src="/front/js/mixitup.min.js"></script>
    <script src="/front/js/owl.carousel.min.js"></script>
    <script src="/front/js/main.js"></script>
    <script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>
    <script>
        window.addEventListener('showToastr', function(event) {
            toastr.remove();
            if (event.detail[0].type === 'info') {
                toastr.info(event.detail[0].message);
            } else if (event.detail[0].type === 'success') {
                toastr.success(event.detail[0].message);
            } else if (event.detail[0].type === 'error') {
                toastr.error(event.detail[0].message);
            } else if (event.detail[0].type === 'warning') {
                toastr.warning(event.detail[0].message);
            } else {
                return false;
            }
        });
    </script>
    <script>
        if (navigator.userAgent.indexOf("Firefox") != -1) {
            history.pushState(null, null, document.URL);
            window.addEventListener("popstate", function() {
                history.pushState(null, null, document.URL);
            });
        }
    </script>
    <!-- js -->
    {{-- <script src="/back/vendors/scripts/core.js"></script> --}}
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    <script src="/back/src/plugins/jquery-steps/jquery.steps.js"></script>
    <script src="/back/vendors/scripts/steps-setting.js"></script>
    <script src="/back/src/plugins/switchery/switchery.min.js"></script>
    <!-- Datatable Setting js -->
    <script src="/back/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/back/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="/back/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="/back/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <!-- buttons for Export datatable -->
    <script src="/back/src/plugins/datatables/js/dataTables.buttons.min.js"></script>
    <script src="/back/src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="/back/src/plugins/datatables/js/buttons.print.min.js"></script>
    <script src="/back/src/plugins/datatables/js/buttons.html5.min.js"></script>
    <script src="/back/src/plugins/datatables/js/buttons.flash.min.js"></script>
    <script src="/back/src/plugins/datatables/js/pdfmake.min.js"></script>
    <script src="/back/src/plugins/datatables/js/vfs_fonts.js"></script>
    <script src="/back/vendors/scripts/datatable-setting.js"></script>

    {{-- tambahan --}}
    <!-- Lightbox2 JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        window.addEventListener('showToastr', function(event) {
            toastr.remove();
            if (event.detail[0].type === 'info') {
                toastr.info(event.detail[0].message);
            } else if (event.detail[0].type === 'success') {
                toastr.success(event.detail[0].message);
            } else if (event.detail[0].type === 'error') {
                toastr.error(event.detail[0].message);
            } else if (event.detail[0].type === 'warning') {
                toastr.warning(event.detail[0].message);
            } else {
                return false;
            }
        });
    </script>
    @livewireScripts
    @stack('scripts')



</body>

</html>
