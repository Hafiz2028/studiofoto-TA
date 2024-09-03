<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('pageTitle')</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            overflow-x: hidden;
        }

        .login-header {
            background-image: url('/back/vendors/images/login-page-img-2.png');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
            height: 9vh;
            /* Adjust as needed */
        }

        .blur-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(10px);
            /* Adjust the blur amount as needed */
            background-color: rgba(255, 255, 255, 0.5);
            /* Adjust the opacity and color */
            z-index: 1;
        }

        .container-fluid {
            position: relative;
            /* Ensure z-index works correctly */
            z-index: 2;
            /* Place above the blur overlay */
        }

        .login-wrap {
            background-image: url('/back/vendors/images/login-page-img-2.png');
            background-size: cover;
            background-position: center;
            height: 70vh;
            /* Adjust as needed */
            max-width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-content {
            background-color: rgba(255, 255, 255, 0.9);
            /* Adjust the opacity and color */
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .login-content h2 {
            margin-bottom: 20px;
        }

        .login-content p {
            margin-bottom: 20px;
        }

        .brand-logo img {
            max-width: 150px;
            /* Adjust logo size */
        }

        .login-menu ul {
            list-style-type: none;
            padding: 0;
        }

        .login-menu ul li {
            display: inline-block;
            margin-right: 10px;
        }

        .login-menu ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .login-menu ul li a:hover {
            color: #666;
        }
    </style>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @livewireStyles
    @stack('stylesheets')
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="blur-overlay"></div>
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
                                <li><a href="{{ route('owner.register') }}" style="color:#e27201;">Register</a></li>
                            @else
                                <li><a href="{{ route('owner.login') }}" style="color:#e27201;">Login</a></li>
                            @endif
                        @endif
                        @if (Route::is('customer.*'))
                            @if (Route::is('customer.login'))
                                <li><a href="{{ route('customer.register') }}" style="color:#e27201;">Register</a></li>
                            @else
                                <li><a href="{{ route('customer.login') }}" style="color:#e27201;">Login</a></li>
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
                {{-- <div class="col-md-6 col-lg-7">
                    <img src="/back/vendors/images/login-page-img.png" alt="" />
                    </div> --}}
                <div class="col-md-12 col-lg-12">
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
        document.addEventListener('livewire:init',()=>{
            Livewire.on('showToastr',(event)=>{
                toastr.remove();
            if(event[0].type === 'info'){ toastr.info(event[0].message); }
            else if(event[0].type === 'success') {toastr.success(event[0].message);}
            else if(event[0].type === 'error'){toastr.error(event[0].message);}
            else {return false;}
            });
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>

</html>
