<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('pageTitle')</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/back/vendors/images/favicon-180x180.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/back/vendors/images/favicon-32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/back/vendors/images/favicon-16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />
    <link rel="stylesheet" href="/extra-assets/ijabo/ijabo.min.css">
    <link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css">
    @livewireStyles
    @stack('stylesheets')
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('home') }}"> <img src="/back/vendors/images/logo_name.png" alt="">
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    @if (!Route::is('admin.*'))
                        @if (Route::is('owner.*'))
                            @if (Route::is('owner.login'))
                                <li><a href="{{ route('owner.register') }}">Register</a></li>
                            @else
                                <li><a href="{{ route('owner.login') }}">Login</a></li>
                            @endif
                        @endif
                        @if (Route::is('customer.*'))
                            @if (Route::is('customer.login'))
                                <li><a href="{{ route('customer.register') }}">Register</a></li>
                            @else
                                <li><a href="{{ route('customer.login') }}">Login</a></li>
                            @endif
                        @endif
                    @endif

                </ul>
            </div>

        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="/back/vendors/images/login-page-img.png" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    <script>
        if (navigator.userAgent.indexOf("Firefox") != -1) {
            history.pushState(null, null, document.URL);
            window.addEventListener("popstate", function() {
                history.pushState(null, null, document.URL);
            });
        }
    </script>
    <script src="/extra-assets/ijabo/ijabo.min.css"></script>
    {{-- <script src="/extra-assets/ijabo/jquery.ijaboViewer.min.js"></script> --}}
    <script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>
    <script>
        window.addEventListener('showToaster', function(event) {
            toastr.remove();
            if (event.detail[0].type === 'info') {
                toastr.info(event.detail[0].message);
            } else if (event.detail[0].type === 'success') {
                toastr.success(event.detail[0].message);
            } else if (event.detail[0].type === 'error') {
                toastr.error(event.detail[0].message);
            } else {
                return false;
            }
        });
        // document.addEventListener('livewire:init',()=>{
        //     Livewire.on('showToastr',(event)=>{
        //         toastr.remove();
        //     if(event[0].type === 'info'){ toastr.info(event[0].message); }
        //     else if(event[0].type === 'success') {toastr.success(event[0].message);}
        //     else if(event[0].type === 'error'){toastr.error(event[0].message);}
        //     else {return false;}
        //     });
        // });
    </script>
    @livewireScripts
    @stack('scripts')
</body>

</html>
