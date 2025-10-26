<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f5f7fb;
        }

        .app-navbar {
            background: linear-gradient(90deg, #0b3d91, #1e40af);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
        }

        .app-navbar .navbar-brand,
        .app-navbar .nav-link,
        .app-navbar .navbar-text {
            color: #fff !important;
        }

        .page-header {
            margin: 1.5rem 0;
        }
    </style>
</head>

<body>
    @unless (request()->routeIs(['login.show','register.show','dashboard.*','pelanggan.*','admin.*']))
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="/">Rental Playstation</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @auth
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-light">Logout</button>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login.show') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register.show') }}">Register</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @endunless
    @if (request()->routeIs(['login.show','register.show','dashboard.*','pelanggan.*','admin.*']))
        @yield('content')
    @else
        <div class="container py-4">
            @yield('content')
        </div>
    @endif
    <!-- Flash messages -->
    <div id="flash-message-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    
    <script>
        // Function to show flash messages
        function showFlashMessage(message, type = 'success') {
            const container = document.getElementById('flash-message-container');
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.style = "min-width: 300px; margin-bottom: 10px;";
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            container.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
        
        // Show any existing flash messages from Laravel
        @if(session('success'))
            showFlashMessage('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showFlashMessage('{{ session('error') }}', 'danger');
        @endif
    </script>
</body>

</html>
