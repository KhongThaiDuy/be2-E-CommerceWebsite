<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Casmart - Biggest shopping center</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="{{ asset('assets/favicon.svg') }}" type="image/svg+xml">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Link Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</head>

<body>

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

        <div class="overlay" data-overlay></div>

        <div class="header-search">
            <input type="search" name="search" placeholder="Search Product..." class="input-field">

            <button class="search-btn" aria-label="Search">
                <ion-icon name="search-outline"></ion-icon>
            </button>
        </div>

        <a href="#" class="logo">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Casmart logo" width="130" height="31">
        </a>

        
        @auth
    <!-- Hiển thị tên người dùng khi đã đăng nhập -->
    <div class="header-action-btn">
        <p class="header-action-label">Hello {{ Auth::user()->full_name }}</p>
    </div>

    <form method="POST" action="{{ route('dangxuat') }}" style="display: inline;">
</form>
@endauth







        <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="navbar" data-navbar>
            <div class="navbar-top">
                <a href="#" class="logo">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="Casmart logo" width="130" height="31">
                </a>
                <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>

            <ul class="navbar-list">
                <li><a href="#home" class="navbar-link">Home</a></li>
                <li><a href="#" class="navbar-link">Shop</a></li>
                <li><a href="#" class="navbar-link">About</a></li>
                <li><a href="{{ route('blogs.home') }}" class="navbar-link">Blog</a></li>
                <li><a href="#" class="navbar-link">Contact</a></li>

                @auth
                    @if (Auth::user()->role === 'admin')
                        <li><a href="{{ route('admin-dashboard') }}" class="navbar-link text-red-500 font-bold">Dashboard</a></li>
                    @endif
                @endauth
                <li>
                    <form method="POST" action="{{ route('dangxuat') }}">
                        @csrf
                        <button type="submit" class="navbar-link text-red-500 font-bold">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</header>



    <main class="flex-1 p-6 bg-gray-50">
            @yield('content')
    </main>




  <!-- 
    - custom js link
  -->
  <script src="./assets/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>