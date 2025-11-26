<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PlayStation Rental - Sistem Manajemen Rental PlayStation">
    <meta name="theme-color" content="#003087">
    <title>@yield('title', 'Rental PlayStation')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/playstation-bg.jpg') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Auth CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    
    @stack('styles')
</head>
<body class="auth-page">
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-left">
            <div class="auth-brand">
                <i class="fa-brands fa-playstation"></i>
                <h2>RENTAL PS</h2>
                <p>Experience the Next Level</p>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="auth-right">
            @yield('content')
        </div>
    </div>
    
    @stack('scripts')
    <script>
        // Password toggle functionality
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.input-wrapper').querySelector('input');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                // Toggle icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>
