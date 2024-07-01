<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('pageTitle')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/back/vendors/images/favicon-180x180.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/back/vendors/images/favicon-32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/back/vendors/images/favicon-16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- Pengaturan tanggal untuk booking -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Daterangepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1/daterangepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1/daterangepicker.min.js"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/jquery-steps/jquery.steps.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/switchery/switchery.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.2/jquery-ui.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.2/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.2/jquery-ui.theme.min.css">
    <!-- SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- untuk modal cek jadwal sblm mulai foto --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- OwlCarousel -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js"
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != "dataLayer" ? "&l=" + l : "";
            j.async = true;
            j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-NXZMQSS");
    </script>
    @livewireStyles
    @stack('stylesheets')
</head>

<body>
    <div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="/back/vendors/images/logo_name.png" alt="" />
				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Loading...</div>
			</div>
		</div>

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>

        </div>
        <div class="header-right">
            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                        <i class="dw dw-settings2"></i>
                    </a>
                </div>
            </div>


            @livewire('admin-owner-header-profile-info')

        </div>
    </div>

    <div class="right-sidebar">
        <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
                Layout Settings
                <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
        <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
                <h4 class="weight-600 font-18 pb-10">Header Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
                <div class="sidebar-radio-group pb-10 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input"
                            value="icon-style-1" checked="" />
                        <label class="custom-control-label" for="sidebaricon-1"><i
                                class="fa fa-angle-down"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-2" />
                        <label class="custom-control-label" for="sidebaricon-2"><i
                                class="ion-plus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-3" />
                        <label class="custom-control-label" for="sidebaricon-3"><i
                                class="fa fa-angle-double-right"></i></label>
                    </div>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
                <div class="sidebar-radio-group pb-30 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-1" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-1" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-1"><i
                                class="ion-minus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-2" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-2" />
                        <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o"
                                aria-hidden="true"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-3" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-3" />
                        <label class="custom-control-label" for="sidebariconlist-3"><i
                                class="dw dw-check"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-4" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-4" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-4"><i
                                class="icon-copy dw dw-next-2"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-5" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-5" />
                        <label class="custom-control-label" for="sidebariconlist-5"><i
                                class="dw dw-fast-forward-1"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-6" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-6" />
                        <label class="custom-control-label" for="sidebariconlist-6"><i
                                class="dw dw-next"></i></label>
                    </div>
                </div>

                <div class="reset-options pt-30 text-center">
                    <button class="btn btn-danger" id="reset-settings">
                        Reset Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="/">
                <img src="/back/vendors/images/logo_name.png" alt="" class="dark-logo" />
                <img src="/back/vendors/images/logo_name.png" alt="" class="light-logo" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">

                    @if (Route::is('admin.*'))
                        <li class="dropdown">
                            <a href="{{ route('admin.home') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.home') ? 'active' : '' }}">
                                <span class="micon bi bi-house"></span><span class="mtext">Home</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;"
                                class="dropdown-toggle {{ Route::is('admin.user.*') ? 'active' : '' }} ">
                                <span class="micon bi bi-people"></span>
                                <span class="mtext">Users</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('admin.user.adminList') }}">Admin</a></li>
                                <li><a href=" {{ route('admin.user.owner.index') }}">Owner</a></li>
                                <li><a href="{{ route('admin.user.customer.index') }}">Customer</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;"
                                class="dropdown-toggle {{ Route::is('admin.venue.*') ? 'active' : '' }}">
                                <span class="micon bi bi-building"></span><span class="mtext">Venue</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('admin.venue.need-approval') }}">Need Approval</a></li>
                                <li><a href="{{ route('admin.venue.approved') }}">Approved</a></li>
                                <li><a href="{{ route('admin.venue.rejected') }}">Rejected</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('admin.service.venueServiceList') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.service.venueServiceList') ? 'active' : '' }}">
                                <span class="micon bi bi-journal-text"></span><span class="mtext">Venue's
                                    Service</span>
                            </a>
                        </li>
                        <li>
                        @elseif (Route::is('owner.*'))
                        <li class="dropdown">
                            <a href="{{ route('owner.home') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('owner.home') ? 'active' : '' }}">
                                <span class="micon bi bi-house"></span><span class="mtext">Home</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('owner.venue.index') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('owner.venue.*') || Route::is('owner.service.*') ? 'active' : '' }}">
                                <span class="micon bi bi-building"></span><span class="mtext">Venue's Manage</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('owner.booking.index') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('owner.booking.*') ? 'active' : '' }}">
                                <span class="micon bi bi-journal-bookmark-fill"></span><span
                                    class="mtext">Booking</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('owner.history.index') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('owner.history.*') ? 'active' : '' }}">
                                <span class="micon bi bi-clock-history"></span><span class="mtext">History</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('owner.transaction.index') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('owner.transaction.*') ? 'active' : '' }}">
                                <span class="micon bi bi-wallet2"></span><span class="mtext">Transaction</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div>
                    @yield('content')
                </div>
            </div>
            <div class="footer-wrap pd-20 mb-20 card-box">
                DeskApp - Bootstrap 4 Admin Template By
                <a href="https://github.com/dropways" target="_blank">Ankit Hingarajiya</a>
            </div>
        </div>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <!-- js -->
    <script src="/back/vendors/scripts/core.js"></script>
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
    <!-- Datatable Setting js -->


    <script>
        if (navigator.userAgent.indexOf("Firefox") != -1) {
            history.pushState(null, null, document.URL);
            window.addEventListener("popstate", function() {
                history.pushState(null, null, document.URL);
            });
        }
    </script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>



    <!-- IjaboCropTool -->
    <script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>
    <script src="/extra-assets/jquery-ui-1.13.2/jquery-ui.min.js"></script>
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
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    {{-- @kropifyScripts --}}
    @livewireScripts
    @stack('scripts')
</body>

</html>
