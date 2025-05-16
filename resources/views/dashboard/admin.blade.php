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

  <!-- Local CSS -->
  <link rel="stylesheet" href="{{ asset('asset/libs/datatables.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('asset/css/style-dashboardcard.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/style-dashboardcard.css') }}" />
</head>

<body class="bg-gray-100 text-gray-800 font-[Rubik]">
  <div class="main_container flex min-h-screen">

    <!-- Sidebar -->
    <aside class="bg-white shadow-md w-64 hidden md:block" id="aside">
      <div class="p-4 border-b flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
          <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="h-8">
          <span class="font-bold text-xl text-gray-700">Admin</span>
        </a>
      </div>

      <nav class="px-4 py-6">
        <ul class="space-y-2">
          <li>
            <a href="{{ route('admin-dashboard') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-gauge"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ route('user.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>User</span>
            </a>
          </li>
          <li>
            <a href="{{ route('product.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>Product</span>
            </a>
          </li>
          <li>
            <a href="{{ route('blogs.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>Blogs</span>
            </a>
          </li>
          <li>
            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>Categories</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="content flex-1 flex flex-col">

      <!-- Header -->
      <header class="bg-white shadow p-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <button class="text-2xl md:hidden"><i class="fa-solid fa-bars"></i></button>
          <form method="POST" action="{{ route('dangxuat') }}">
            @csrf
            <button type="submit" class="text-red-500 font-semibold flex items-center gap-2 hover:underline">
              <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
          </form>
        </div>

        <div class="flex items-center gap-6">
          <div class="hidden md:block">
            <input type="text" name="search" value="{{ request('search') }}"
              class="border rounded px-3 py-1 focus:outline-none" placeholder="Search..." />
          </div>

          <button class="text-xl"><i class="fa-regular fa-sun"></i></button>

          <div class="relative group">
            <img src="./assets/image/user.png" alt="User" class="rounded-full w-10 h-10 cursor-pointer" />
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-10">
              <div class="bg-blue-500 text-white p-4 flex gap-3 items-center rounded-t-md">
                <img src="./assets/image/user.png" alt="User" class="w-10 h-10 rounded-full border" />
                <div>
                  <h1 class="font-semibold">John Doe</h1>
                  <p class="text-sm">johndoe@gmail.com</p>
                </div>
              </div>
              <ul class="py-2">
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

      </header>

      <!-- Content Area -->

    </main>
  </div>



  <!-- Scripts -->
  <script src="{{ asset('asset/libs/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('asset/libs/datatables.min.js') }}"></script>
  <script src="{{ asset('asset/libs/chart.js') }}"></script>
  <script src="{{ asset('asset/js/index.js') }}"></script>
</body>

</html>