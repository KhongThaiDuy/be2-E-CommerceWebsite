<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Rubik:wght@300;400;700&display=swap"
    rel="stylesheet">

  <!-- Local CSS (asset helper) -->
  <link rel="stylesheet" href="{{ asset('asset/libs/datatables.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('asset/css/style-dashboardcard.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/style-dashboardcard.css') }}" />
</head>

<body>
  <div class="main_container flex">

    <!-- Sidebar -->
    <aside class="aside select-none hidden md:block" id="aside">
      <div class="sidebar_header flex justify-between items-center px-4">
        <div class="logo flex items-center gap-2">
          <img src="./assets/images/logo.svg" alt="Logo" class="expent_logo" />
          
        </div>
        <div class="toggler">
          <i class="fa-regular fa-circle-dot" id="toggler"></i>
        </div>
      </div>

      <div class="sidebar_body">
       

          <li class="sidevar_item_wrapper">
            <div class="sidebar_item flex items-center gap-2">
              <i class="fa-solid fa-gauge"></i>
              <span class="title">Dashboard</span>
            </div>
          </li>

          
          <div class="sidebar_item flex items-center gap-2">
              <i class="fa-solid fa-user"></i>
              <a href="{{ route('user.index') }}" class="title">User</a>
          </div>

          

          <li class="sidevar_item_wrapper">
            <div class="sidebar_item flex items-center gap-2">
              <i class="fa-solid fa-user-shield"></i>
              <span class="title">Role & Permission</span>
            </div>
            <div class="sidebar_link_item hidden">
              <ul class="link_item_wrapper">
                <li class="link_item">
                  <a href="#" class="flex items-center gap-2">
                    <i class="fa-regular fa-circle link_item_icon"></i> Admin Dashboard
                  </a>
                </li>
                <li class="link_item">
                  <a href="#" class="flex items-center gap-2">
                    <i class="fa-regular fa-circle link_item_icon"></i> Ecommerce Dashboard
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="sidevar_item_wrapper">
            <div class="sidebar_item flex items-center gap-2">
              <i class="fa-solid fa-chart-bar"></i>
              <span class="title">Category</span>
            </div>
            <div class="sidebar_link_item hidden">
              <ul class="link_item_wrapper">
                <li class="link_item">
                  <a href="#" class="flex items-center gap-2">
                    <i class="fa-regular fa-circle link_item_icon"></i> Admin Dashboard
                  </a>
                </li>
                <li class="link_item">
                  <a href="#" class="flex items-center gap-2">
                    <i class="fa-regular fa-circle link_item_icon"></i> Ecommerce Dashboard
                  </a>
                </li>
              </ul>
            </div>
          </li>

        
      </div>
    </aside>

    <!-- Content -->
    <main class="contant flex-1 flex flex-col">

      <!-- Header -->
      <header class="header flex justify-between items-center p-4 shadow">
        <div class="header-left flex items-center gap-4">
          <div class="mobile_toggler text-2xl cursor-pointer md:hidden">
            <i class="fa-solid fa-bars"></i>
          </div>
          <form method="POST" action="{{ route('dangxuat') }}">
        @csrf
        <button type="submit" class="navbar-link text-red-500 font-bold flex items-center gap-2">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </form>
        </div>

        <div class="header-right flex items-center gap-6">
          <div class="search-box hidden md:block">
            <input type="text" name="search" class="input-search" placeholder="Search..." />
          </div>
          <div class="theme-toggler">
            <i class="fa-regular fa-sun text-2xl cursor-pointer" id="theme-toggler"></i>
          </div>

          <div class="user-profile relative dropdown cursor-pointer">
            <div class="nav-profile-img-container">
            <img src="./assets/image/user.png" alt="User" class="rounded-full w-10 h-10" />

            </div>

            <div class="dropdown-wrapper hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
              <div class="dropdown-contant">

                <div class="user-info bg-[var(--primary-color)] text-white flex items-center gap-3 px-4 py-2">
                  <div class="image w-12 h-12 rounded-full border p-1">
                  <img src="./assets/image/user.png" alt="User" class="rounded-full w-10 h-10" />

                  </div>
                  <div class="user-detail">
                    <h1>John Doe</h1>
                    <p>johndoe@gmail.com</p>
                  </div>
                </div>

                <ul class="dropdown-link flex flex-col">
                  <li>
                    <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                      <i class="fa-regular fa-user"></i> Profile
                    </a>
                  </li>
                  <li>
                    <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                      <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                  </li>
                </ul>

              </div>
            </div>
          </div>

        </div>
      </header>

      

    </main>

  </div>

  <!-- Scripts -->
  <script src="{{ asset('asset/libs/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('asset/libs/datatables.min.js') }}"></script>
  <script src="{{ asset('asset/libs/chart.js') }}"></script>
  <script src="{{ asset('asset/js/index.js') }}"></script>

</body>

</html>
