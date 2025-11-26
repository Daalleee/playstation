<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rental PlayStation') - PlayStation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #0b3d91;
            --primary-light: #1e40af;
            --secondary: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --card-bg: #ffffff;
            --header-bg: linear-gradient(135deg, #0b3d91, #1e40af);
        }

        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .ecommerce-navbar {
            background: var(--header-bg);
            color: white;
        }

        .ecommerce-navbar .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            padding: 0.25rem 0.5rem;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .ecommerce-navbar .nav-link:hover,
        .ecommerce-navbar .nav-link.active {
            color: white !important;
        }

        .cart-badge {
            position: relative;
            top: -3px;
            left: -3px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--light-gray);
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 180px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-content {
            padding: 1rem;
        }

        .product-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .product-subtitle {
            color: var(--gray);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .product-stock {
            font-size: 0.875rem;
            color: var(--gray);
            margin-top: 0.25rem;
        }

        .btn-shopping {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-shopping:hover {
            background: linear-gradient(135deg, var(--primary-light), #3b82f6);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(11, 61, 145, 0.3);
        }

        .hero-section {
            background: linear-gradient(135deg, #0b3d91, #1e40af);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--dark);
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }

        .featured-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: var(--danger);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .add-to-cart-btn {
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            transform: scale(1.05);
        }

        .footer-section {
            background: var(--header-bg);
            color: white;
            padding: 3rem 0 1rem;
        }

        .category-nav {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .category-item {
            display: inline-block;
            margin: 0.25rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            background: var(--light);
            border: 1px solid var(--light-gray);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-item.active,
        .category-item:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .search-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .search-input {
            border-radius: 50px;
            border: 1px solid var(--light-gray);
            padding-left: 2.5rem;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
    </style>
</head>

<body>
    <!-- Single Unified Sticky Navbar -->
    <nav class="ecommerce-navbar sticky-top" style="background: var(--header-bg); z-index: 1020;">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between" style="padding: 0.6rem 0;">
                <!-- Logo on the left -->
                <div class="d-flex align-items-center">
                    <i class="bi bi-playstation fs-4 me-2"></i>
                    <span class="h6 mb-0 fw-bold text-white">PlayStation</span>
                </div>

                <!-- Mobile menu button -->
                <button class="navbar-toggler d-md-none border-0 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list text-white fs-3"></i>
                </button>

                <!-- Navigation links in the center - Collapsible for mobile -->
                <div class="collapse navbar-collapse d-md-flex justify-content-center" id="navbarNav">
                    <ul class="nav mb-0">
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('dashboard.pelanggan') ? 'active' : '' }}"
                                href="{{ route('dashboard.pelanggan') }}">
                                <i class="bi bi-house me-1"></i> Beranda
                            </a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('pelanggan.unitps.*') ? 'active' : '' }}"
                                href="{{ route('pelanggan.unitps.index') }}">
                                <i class="bi bi-controller me-1"></i> Unit PS
                            </a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('pelanggan.games.*') ? 'active' : '' }}"
                                href="{{ route('pelanggan.games.index') }}">
                                <i class="bi bi-disc me-1"></i> Games
                            </a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('pelanggan.accessories.*') ? 'active' : '' }}"
                                href="{{ route('pelanggan.accessories.index') }}">
                                <i class="bi bi-plugin me-1"></i> Aksesoris
                            </a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('pelanggan.rentals.index') ? 'active' : '' }}"
                                href="{{ route('pelanggan.rentals.index') }}">
                                <i class="bi bi-clock-history me-1"></i> Riwayat
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Icons on the right - Visible on medium and larger screens, hidden on mobile -->
                <div class="d-none d-md-flex align-items-center" style="gap: 25px;">
                    <a href="{{ route('pelanggan.cart.index') }}"
                        class="text-white text-decoration-none position-relative d-flex align-items-center me-4">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="cart-badge">
                            {{ auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->count() : 0 }}
                        </span>
                    </a>

                    <div class="dropdown">
                        <a href="#"
                            class="text-white text-decoration-none dropdown-toggle d-flex align-items-center"
                            id="navbarDropdown" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('pelanggan.profile.show') }}">Profil Saya</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('pelanggan.rentals.index') }}">Riwayat
                                    Penyewaan</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Mobile menu for right icons -->
                <div class="d-md-none dropdown">
                    <a href="#" class="text-white text-decoration-none" id="mobileMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical fs-4"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('pelanggan.cart.index') }}">
                                <i class="bi bi-cart me-2"></i> Keranjang
                                <span class="badge bg-secondary ms-auto">{{ auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->count() : 0 }}</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('pelanggan.profile.show') }}"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('pelanggan.rentals.index') }}"><i class="bi bi-clock-history me-2"></i> Riwayat Penyewaan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-section mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>PlayStation</h5>
                    <p>Tempat terbaik untuk menyewa PlayStation, games, dan aksesoris dengan harga terjangkau.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Layanan</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('pelanggan.unitps.index') }}" class="text-white">Sewa Unit PS</a></li>
                        <li><a href="{{ route('pelanggan.games.index') }}" class="text-white">Sewa Games</a></li>
                        <li><a href="{{ route('pelanggan.accessories.index') }}" class="text-white">Aksesoris</a></li>
                        <li><a href="{{ route('pelanggan.cart.index') }}" class="text-white">Keranjang</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Hubungi Kami</h5>
                    <p>
                        <i class="bi bi-geo-alt me-2"></i> Sleman, Yogyakarta<br>
                        <i class="bi bi-envelope me-2"></i> BangDall@gmail.com<br>
                        <i class="bi bi-telephone me-2"></i> 0821-6391-6419
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} PlayStation. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Flash messages -->
    <div id="flash-message-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
        @if (session('success'))
            showFlashMessage('{{ session('success') }}', 'success');
        @endif
        @if (session('error'))
            showFlashMessage('{{ session('error') }}', 'danger');
        @endif

        // Add to cart functionality
        function addToCart(itemType, itemId, quantity = 1) {
            fetch('{{ route('pelanggan.cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json', // This is important for Laravel to return JSON
                        'X-Requested-With': 'XMLHttpRequest' // Additional header for AJAX detection
                    },
                    body: JSON.stringify({
                        type: itemType,
                        id: itemId,
                        quantity: quantity,
                        price_type: itemType === 'unitps' ? 'per_jam' : 'per_hari'
                    })
                })
                .then(response => {
                    // Check if response is JSON or HTML
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, try to handle as text/HTML and look for common error indicators
                        return response.text().then(text => {
                            // Check if the response contains an error indicator
                            if (response.status >= 400) {
                                throw new Error('Server error: ' + response.status);
                            }
                            // If it's HTML but status is OK, return a general success
                            return {
                                success: true,
                                message: 'Item berhasil ditambahkan ke keranjang!'
                            };
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        showFlashMessage(data.message, 'success');
                        // Update cart badge count
                        updateCartBadge();
                    } else {
                        showFlashMessage(data.message || 'Gagal menambahkan item ke keranjang', 'danger');
                    }
                })
                .catch(error => {
                    // Check if the error message contains HTML
                    if (error.message.includes('<')) {
                        showFlashMessage('Terjadi kesalahan server. Silakan coba lagi.', 'danger');
                    } else {
                        showFlashMessage('Terjadi kesalahan: ' + error.message, 'danger');
                    }
                });
        }

        // Update cart badge count from API
        function updateCartBadge() {
            fetch('{{ route('pelanggan.cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.cart-badge');
                    if (badge) {
                        badge.textContent = data.count || 0;
                    }
                })
                .catch(error => {
                    console.error('Error updating cart badge:', error);
                    // Fallback: try to update based on current badge value + 1 (less accurate but better than nothing)
                    const badge = document.querySelector('.cart-badge');
                    if (badge) {
                        let count = parseInt(badge.textContent) || 0;
                        badge.textContent = count + 1;
                    }
                });
        }

        // Initialize cart badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartBadge();
        });
    </script>
</body>

</html>
