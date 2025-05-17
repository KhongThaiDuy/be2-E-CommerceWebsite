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

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


<style>
  .dropdown-container {
      position: relative;
      display: inline-block;
  }


  .dropdown-btn {
      
      color: black;
      padding: 10px 16px;
      border: none;
      cursor: pointer;
      font-weight: ;
      border-radius: 5px;
  }

  .dropdown-list {
      display: none;
      position: absolute;
      background-color: #fff;
      min-width: 200px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
      border-radius: 6px;
      z-index: 1;
      margin-top: 4px;
      padding: 0;
  }

  .dropdown-list li {
      list-style: none;
      padding: 10px;
      border-bottom: 1px solid #eee;
  }

  .dropdown-list li a {
      text-decoration: none;
      color: #333;
      display: block;
  }

  .dropdown-list li:hover {
      background-color: #f0f0f0;
  }
</style>
</head>

<body>

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
  <div class="container" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">

    <div class="overlay" data-overlay></div>

    <!-- Thanh tìm kiếm -->
    <div class="header-search" style="flex: 1; max-width: 400px; position: relative;">
      <input type="search" name="search" placeholder="Search Product..." class="input-field" style="width: 100%; padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 4px;">
      <button class="search-btn" aria-label="Search" style="position: absolute; top: 50%; right: 8px; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
        <ion-icon name="search-outline"></ion-icon>
      </button>
    </div>

    <!-- Logo -->
    <a href="#" class="logo" style="flex-shrink: 0;">
      <img src="{{ asset('assets/images/logo.svg') }}" alt="Casmart logo" width="130" height="31">
    </a>

    <!-- Cart button -->
    <button class="header-action-btn" style="position: relative; background: none; border: none; cursor: pointer; display: flex; flex-direction: column; align-items: center;">
      <ion-icon name="cart-outline" aria-hidden="true" style="font-size: 1.5rem;"></ion-icon>
      <p class="header-action-label" style="font-size: 0.85rem;">Cart</p>
      <div class="btn-badge green" aria-hidden="true" style="position: absolute; top: 0; right: 0; background-color: green; color: white; border-radius: 50%; padding: 0 6px; font-size: 0.7rem;">3</div>
    </button>

    <!-- Hiển thị tên người dùng nếu đã đăng nhập -->
    @auth
      <div class="header-action-btn" style="display: flex; align-items: center; font-size: 0.9rem; padding-left: 1rem;">
        <p class="header-action-label">Hello {{ Auth::user()->full_name }}</p>
      </div>
    @endauth

    <!-- Navbar -->
    <nav class="navbar" data-navbar style="margin-left: 1rem;">
      <div class="navbar-top" style="margin-bottom: 1rem;">
        <a href="#" class="logo">
          <img src="{{ asset('assets/images/logo.svg') }}" alt="Casmart logo" width="130" height="31">
        </a>
      </div>
      
            <ul class="navbar-list">
                <li><a href="{{ route('dashboard') }}" class="navbar-link">Home</a></li>
                <li><a href="{{ route('products.home') }}" class="navbar-link">Shop</a></li>
         
                <div class="dropdown-container">
                  <button class="dropdown-btn" onclick="toggleDropdown()">Categories</button>
                  <ul class="dropdown-list" id="dropdownList">
                      @php
                          $categories = \App\Models\Category::all();
                      @endphp
                      @foreach ($categories as $category)
                          <li>
                              <a href="{{ route('categories.products', $category->category_id) }}">
                                  {{ $category->category_name }}
                              </a>
                          </li>
                      @endforeach
                  </ul>
              </div>

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

<script>
  function toggleDropdown() {
    const list = document.getElementById('dropdownList');
    list.style.display = (list.style.display === 'block') ? 'none' : 'block';
  }

  // Optional: click outside to close
  document.addEventListener('click', function(e) {
    const btn = document.querySelector('.dropdown-btn');
    const list = document.getElementById('dropdownList');
    if (!btn.contains(e.target) && !list.contains(e.target)) {
      list.style.display = 'none';
    }
  });
</script>