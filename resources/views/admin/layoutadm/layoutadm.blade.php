<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
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
      position: fixed; /* Make the navbar fixed */
      top: 0; /* Align it to the top */
      left: 0;
      right: 0;
      z-index: 1000; /* Ensure it is above other elements */
    }

    .navbar .nav-link, .navbar .nav-item .dropdown-toggle {
      color: #7773d4;
      font-size: 16px;
    }

    /* Adjust content wrapper to avoid overlap with navbar */
    .content-wrapper {
      margin-top: 56px; /* Adjust this based on your navbar height */
    }

    /* Sidebar Style */
    .main-sidebar {
      background-color: #e1e5f8;
    }

    .nav-sidebar .nav-item .nav-link {
      color: #3200af; /* Default link color */
      background-color: #e1e5f8; /* Default background color */
    }

    .nav-sidebar .nav-item .nav-link.active,
    .nav-sidebar .nav-item:hover > .nav-link {
      background-color: #7773d4; /* Background color for active and hovered items */
      color: #ffffff; /* Text color for active items */
    }

    /* New Hover Effect for Sidebar */
    .nav-sidebar .nav-item .nav-link:hover {
      color: #ffffff !important; /* Change text color to white on hover */
    }

    .nav-sidebar .nav-item .nav-link:hover i {
      color: #ffffff !important; /* Change icon color to white on hover */
    }

    .nav-sidebar .nav-item .nav-link i {
      color: #3200af; /* Default icon color */
      margin-right: 10px;
    }

    /* Active link styles */
    .nav-sidebar .nav-item .nav-link.active {
      background-color: #7773d4; /* Background color for active items */
      color: #ffffff; /* Text color for active items */
    }

    /* Change icon color when the link is active */
    .nav-sidebar .nav-item .nav-link.active .nav-icon {
      color: white; /* Change icon color to white when active */
    }

    /* Hover effect for sidebar links */
    .nav-sidebar .nav-item:hover > .nav-link {
      background-color: #7773d4; /* Background color for hovered items */
      color: #ffffff; /* Text color for hovered items */
    }

    /* Change icon color on hover */
    .nav-sidebar .nav-item .nav-link:hover .nav-icon {
      color: #ffffff; /* Change icon color to white on hover */
    }

    /* Default icon color */
    .nav-sidebar .nav-item .nav-link .nav-icon {
      color: #3200af; /* Default icon color */
      margin-right: 10px; /* Space between icon and text */
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

          @if (session('profile_picture'))
            <img id="profile_picture" src="{{ asset(session('profile_picture')) }}" alt="Foto Profil" class="rounded-circle" style="width: 30px; height: 30px;">
          @else
            <img id="profile_picture" src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(session('email')))) }}?s=200&d=mp" alt="Foto Profil" class="rounded-circle" style="width: 30px; height: 30px;">
          @endif
          <span>{{ session('username') ? session('username') : session('email') }}</span>
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
        <a href="{{ route('showdashboardadm') }}" class="nav-link {{ Request::is('dashboardadmin') ? 'active' : '' }}">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item">
          <a href="{{ route('admin.organizations') }}" class="nav-link {{ Request::is('admin/organizations*') || Request::is('admin/detailorganization/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-building"></i>
              <p>Organization List</p>
          </a>
      </li>
        </li>
        <li class="nav-item">
          <a href="{{ route('showuserdata') }}" class="nav-link {{ Request::is('userdata') || Request::is('userrole') || Request::is('userrole/createrole') || Request::is('userrole/updaterole/*') || Request::is('moredetailsadm') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('showProducts') }}" class="nav-link {{ Request::is('products') ? 'active' : '' }}">
            <i class="nav-icon fas fa-gift"></i>
            <p>Product and Price</p>
          </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('showLicense') }}" class="nav-link {{ Request::is('licenses') || Request::is('licenses/activity') || Request::is('licenses/hooks') || Request::is('licenses/orders') || Request::is('licenses/serial-numbers') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-contract"></i>
              <p>License</p>
            </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('showtransaction') }}" class="nav-link {{ Request::is('transactionhistory') ? 'active' : '' }}">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<script>
  $(document).ready(function() {
    // Check the stored state and set the sidebar accordingly
    const sidebarState = localStorage.getItem('sidebarState');
    if (sidebarState === 'closed') {
      $('body').addClass('sidebar-collapse'); // Collapse the sidebar
    }

    // Toggle sidebar and save state
    $('[data-widget="pushmenu"]').on('click', function() {
      if ($('body').hasClass('sidebar-collapse')) {
        localStorage.setItem('sidebarState', 'open');
      } else {
        localStorage.setItem('sidebarState', 'closed');
      }
    });

    // Handle logout confirmation
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
