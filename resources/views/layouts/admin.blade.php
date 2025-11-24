<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

     <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

     <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

     <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/file-input.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        @include('layouts.sidebar')
        
        <div class="main-content">
            @include('layouts.admin-header')
            
             <!-- Content -->
            <div class="content-area">
                @yield('content')
            </div>
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

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
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
    @stack('scripts')
</body>
</html>