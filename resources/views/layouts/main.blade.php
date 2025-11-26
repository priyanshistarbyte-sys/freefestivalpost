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

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
   
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">Admin</div>
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
                        <li class="{{ request()->routeIs('user.*') ? 'active' : '' }}"><a href="{{ route('user.index') }}"><span>Users List</span></a></li>
                    </ul>
                </li>
               

                <li>
                    <a href="#">
                        <i class="fa fa-image"></i> <span>Photos List</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('plan.*') ? 'active' : '' }}">
                    <a href="{{ route('plan.index') }}">
                        <i class="fa fa-paw"></i><span>Subscription Plan</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="fa fa-user-circle"></i> <span>Admin</span>
                        <i class="fa fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('admin-user.*') ? 'active' : '' }}"><a href="{{ route('admin-user.index') }}"><span>Users</span></a></li>
                        <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}"><a href="{{ route('roles.index') }}"><span>Roles</span></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-cog"></i> <span>Settings</span>
                    </a>
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
            let parent = $(this).parent();
            
            // Toggle current dropdown
            parent.toggleClass('open');
            parent.find('.submenu').slideToggle();
            
            // Close other dropdowns
            $('.dropdown').not(parent).removeClass('open').find('.submenu').slideUp();
        });
        
        // Auto-open dropdown if submenu is active
        $('.submenu li.active').closest('.dropdown').addClass('open').find('.submenu').show();
    </script>
    @stack('scripts')
</body>

</html>
