<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </head>
    <body style="font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #000e3d 0%, #324991 100%); min-height: 100vh; margin: 0; padding: 0;">
        <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
            <div style="width: 100%; max-width: 400px;">
                <!-- Logo Section -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); margin-bottom: 1.5rem; display: inline-block;">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Brand Logo" style="height: 60px; width: auto;">
                    </div>
                </div>

                <!-- Auth Card -->
                <div style="background: white; border-radius: 12px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); padding: 2.5rem; border-top: 4px solid #2bc32a;">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div style="text-align: center; margin-top: 2rem; color: rgba(255, 255, 255, 0.8); font-size: 0.875rem;">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
</html>
