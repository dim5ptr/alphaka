<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Management Organization</title>

  @yield('head')

  <!-- Google Font: Inter -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
  
  <style>

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f6f9;
    }
    
    /* Navbar Style */
    .navbar {
      background-color: #e2e4fb;
      color: #3200af;
    }
    .navbar .nav-link, .navbar .nav-item .dropdown-toggle {
      color: #7773d4;
      font-size: 16px;
    }

    /* Sidebar Style */
    .main-sidebar {
      background-color: #e1e5f8;
    }
    .nav-sidebar .nav-item .nav-link {
      color: #3200af;
      background-color: #e1e5f8;
    }
    .nav-sidebar .nav-item .nav-link.active, .nav-sidebar .nav-item:hover > .nav-link {
      background-color: #7773d4;
      color: #ffffff;
    }
    .nav-sidebar .nav-item .nav-link i {
      color: #3200af;
      margin-right: 10px;
    }
    .sidebar .nav-item .nav-link p {
      font-weight: 600;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
          @if(session('profile_picture'))
            <img src="{{ session('profile_picture') }}" class="rounded-circle" style="width: 30px; height: 30px;">
          @else
            <i class="fas fa-user-circle"></i>
          @endif
          <span>{{ session('username') }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="{{ route('personaladm') }}">Settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" id="logout">Logout</a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar elevation-4">
    <div class="sidebar">
      <nav class="mt-4">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="{{ route('dashboardadm') }}" class="nav-link {{ Request::is('dashboardadm') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link {{ Request::is('organization') ? 'active' : '' }}">
              <i class="nav-icon fas fa-building"></i>
              <p>Organization List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link {{ Request::is('users') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link {{ Request::is('products') ? 'active' : '' }}">
              <i class="nav-icon fas fa-gift"></i>
              <p>Product and Price</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link {{ Request::is('transaction-history') ? 'active' : '' }}">
              <i class="nav-icon fas fa-history"></i>
              <p>Transaction History</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    @yield('content')
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('template/dist/js/adminlte.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
  $(document).ready(function() {
    $('#logout').click(function() {
      Swal.fire({
        title: 'Logout',
        text: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, logout!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "{{ route('logout') }}";
        }
      });
    });
  });
</script>

@yield('script')
</body>
</html>