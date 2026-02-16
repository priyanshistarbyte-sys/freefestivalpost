<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Free Festival Post') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Override CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-override.css') }}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/file-input.css') }}">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
    <!-- Compact Layout CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/compact-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatables-compact.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/spacing-override.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-switch-fix.css') }}">
    <!-- Custom DataTable Spinner -->
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-spinner.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
   
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Brand Fotos">
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
              
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-tags"></i> <span>Category</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('category.index') ? 'active' : '' }}"><a href="{{ route('category.index') }}"><span>Category</span></a></li>
                        <li class="{{ request()->routeIs('sub-category.*') ? 'active' : '' }}"><a href="{{ route('sub-category.index') }}"><span>Sub Category</span></a></li>
                        <li class="{{ request()->routeIs('home-category.*') ? 'active' : '' }}"><a href="{{ route('home-category.index') }}"><span>Home Category</span></a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-users"></i> <span>Users</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}"><span>Users List</span></a>
                        </li>
                        @can('user-transaction')
                            <li class="{{ request()->routeIs('users.transactions.*') ? 'active' : '' }}">
                                <a href="{{ route('users.transactions.list') }}"><span>User Transaction</span></a>
                            </li>     
                        @endcan
                        <li class="{{ request()->routeIs('post.list') ? 'active' : '' }}">
                            <a href="{{ route('post.list') }}"><span>User Post</span></a>
                        </li> 

                        <li class="{{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                            <a href="{{ route('feedback.list') }}"><span>User Feedback</span></a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs('tamplet.*') ? 'active' : '' }}">
                    <a href="{{ route('tamplet.index') }}">
                        <i class="fa fa-image"></i> <span>Template List</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('videogif.*') ? 'active' : '' }}">
                    <a href="{{ route('videogif.index') }}">
                        <i class="fa fa-film"></i> <span>Video/GIF</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-user-circle"></i> <span>Photos List</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('photo-status.*') ? 'active' : '' }}"><a href="{{ route('photo-status.index') }}"><span>Photo Status</span></a></li>
                        <li class="{{ request()->routeIs('photo.index') ? 'active' : '' }}"><a href="{{ route('photo.index') }}"><span>Photos</span></a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-mobile"></i> <span>Application</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('advertisement.*') ? 'active' : '' }}"><a href="{{ route('advertisement.index') }}"><span>Advertisement</span></a></li>
                        <li class="{{ request()->routeIs('application.*') ? 'active' : '' }}"><a href="{{ route('application.index') }}"><span>Application</span></a></li>
                    </ul>
                </li>
                @can('plan-manage')
                <li class="{{ request()->routeIs('plan.*') ? 'active' : '' }}">
                    <a href="{{ route('plan.index') }}">
                        <i class="fa fa-paw"></i><span>Subscription Plan</span>
                    </a>
                </li>
                @endcan
                @can('report-manage')
                 <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-mobile"></i> <span>Reports</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('report.dayWiseSubscription') ? 'active' : '' }}"><a href="{{ route('report.dayWiseSubscription') }}"><span>Day Wise Subscription</span></a></li>
                        <li class="{{ request()->routeIs('report.monthlySubscription') ? 'active' : '' }}"><a href="{{ route('report.monthlySubscription') }}"><span>Monthly Subscription</span></a></li>
                        <li class="{{ request()->routeIs('report.repeatSubscription') ? 'active' : '' }}"><a href="{{ route('report.repeatSubscription') }}"><span>Repeat Subscription</span></a></li>
                        <li class="{{ request()->routeIs('report.daywiseRegister') ? 'active' : '' }}"><a href="{{ route('report.daywiseRegister') }}"><span>Day Wise User Register</span></a></li>
                    </ul>
                </li>
                @endcan
                @can('admin-user-manage')
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-user-circle"></i> <span>Admin</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        @can('admin-user-manage')<li class="{{ request()->routeIs('admin-user.*') ? 'active' : '' }}"><a href="{{ route('admin-user.index') }}"><span>Users</span></a></li>@endcan
                        @can('role-manage')<li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}"><a href="{{ route('roles.index') }}"><span>Roles</span></a></li>@endcan
                    </ul>
                </li>
                @endcan
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-cog"></i> <span>Site Settings</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        @can('frame-manage')
                        <li class="{{ request()->routeIs('frame.*') ? 'active' : '' }}"><a href="{{ route('frame.index') }}"><span>Frames</span></a></li>
                        @endcan
                        @can('sub-frame-manage')
                        <li class="{{ request()->routeIs('sub-frame.*') ? 'active' : '' }}"><a href="{{ route('sub-frame.index') }}"><span>Sub Frames</span></a></li>
                        @endcan
                        @can('setting-manage')
                        <li class="{{ request()->routeIs('settings') ? 'active' : '' }}"><a href="{{ route('settings') }}"><span>Settings</span></a></li>
                        @endcan
                        @can('font-manage')
                        <li class="{{ request()->routeIs('fonts.*') ? 'active' : '' }}"><a href="{{ route('fonts.index') }}"><span>Fonts</span></a></li>
                        @endcan
                        @can('send-notification-manage')
                        <li class="{{ request()->routeIs('notification.*') ? 'active' : '' }}"><a href="{{ route('notification.index') }}"><span>Send Notification</span></a></li>
                        @endcan
                        @can('coupon-manage')
                        <li class="{{ request()->routeIs('coupon-code.*') ? 'active' : '' }}"><a href="{{ route('coupon-code.index') }}"><span>Coupon Code</span></a></li>
                        @endcan
                        @can('slider-manage')
                        <li class="{{ request()->routeIs('app-slider.*') ? 'active' : '' }}"><a href="{{ route('app-slider.index') }}"><span>Slider</span></a></li>
                        @endcan
                        @can('faq-manage')
                        <li class="{{ request()->routeIs('faqs.*') ? 'active' : '' }}"><a href="{{ route('faqs.index') }}"><span>FAQ</span></a></li>
                        @endcan
                        <li class="{{ request()->routeIs('image-zip.download') ? 'active' : '' }}"><a href="{{ route('image-zip.download') }}"><span>Images Copy</span></a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-user-circle"></i> <span>Payment</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('payment.failed') ? 'active' : '' }}">
                            <a href="{{ route('payment.failed') }}">
                                <span>Payment Failed</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('payment.paid-subscription') ? 'active' : '' }}">
                            <a href="{{ route('payment.paid-subscription') }}">
                                <span>Paid Subscription</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('payment.trial-subscription') ? 'active' : '' }}">
                            <a href="{{ route('payment.trial-subscription') }}">
                                <span>Trial Subscription</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs('complain.*') ? 'active' : '' }}">
                    <a href="{{ route('complain.list') }}">
                        <i class="fa fa-comments"></i> <span>Complain</span>
                    </a>
                </li>
           
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fab fa-whatsapp"></i> <span>Whatsapp</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('whatsapp-media.index') ? 'active' : '' }}">
                            <a href="{{ route('whatsapp-media.index') }}">
                                <span>WhatsApp Media</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('whatsapp-template.index') ? 'active' : '' }}">
                            <a href="{{ route('whatsapp-template.index') }}">
                                <span>WhatsApp Template</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('whatsapp-bulk-send.index') ? 'active' : '' }}">
                            <a href="{{ route('whatsapp-bulk-send.index') }}">
                                <span>WhatsApp Bulk Add</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="">
                                <span>Auto Send Message</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="header-toggle mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-title">
                    @yield('page-title')
                </div>
            </div>
            {{-- <div class="header-actions">
                <div class="dropdown">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="margin-left: 1rem;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div> --}}
            <div class="user-menu">
                <div class="dropdown">
                    <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user"></i>
                                Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @can('setting-manage')
                        <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="fas fa-cog"></i>
                                Setting</a></li>
                        <li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endcan
                      
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>
    <div id="commonModal" class="modal" tabindex="-1" aria-labelledby="exampleModalLongTitle" aria-modal="true"
        role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="{{ asset('assets/js/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

    <!-- SweetAlert JS -->
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <!-- Initialize Toastr Notifications -->
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    <script>
        // Desktop sidebar collapse toggle
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        }

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.toggle('open');

            if (sidebar.classList.contains('open')) {
                overlay.style.display = 'block';
            } else {
                overlay.style.display = 'none';
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target) &&
                sidebar.classList.contains('open')) {
                toggleSidebar();
            }
        });
    </script>
    <script>
        $(".dropdown-toggle").click(function () {
            let parent = $(this).parent('.dropdown');
            // Toggle current dropdown
            if (parent.hasClass('open')) {
                // IF already open → close it
                parent.removeClass('open');
                submenu.slideUp();
            } else {
                // ELSE → open it & close others
                $('.dropdown.open').removeClass('open').find('.submenu').slideUp();
                parent.addClass('open');
                submenu.slideDown();
            }
            
        });
        
        // Auto-open dropdown if submenu is active
        $('.submenu li.active').closest('.dropdown').addClass('open').find('.submenu').show();
        
        // Position submenu on hover for collapsed sidebar
        // $('.sidebar.collapsed .dropdown').hover(function() {
        //     if ($('.sidebar').hasClass('collapsed')) {
        //         const rect = this.getBoundingClientRect();
        //         console.log(react.top);
        //         console.log(react.top-120);
                
        //         $(this).find('.submenu').css('top', rect.top-120 + 'px');
        //     }
        // });
    </script>
    @stack('scripts')
</body>

</html>
