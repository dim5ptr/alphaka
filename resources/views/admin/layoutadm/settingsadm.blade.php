<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel</title>

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
  <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    {{-- <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a id="logout" class="nav-link" href="#">
                <i class="fas fa-sign-out-alt" style="color: #3200af;"></i>
                Logout
            </a>
        </li>
    </ul> --}}
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4">
    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('showdashboardadm') }}" class="nav-link {{ Request::is('dashboardadmin') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>Dashboard</p>
                    </a>
                  </li>
                <li class="nav-item">
                    <a href="{{ route('personaladm') }}" class="nav-link {{ request()->routeIs('personaladm') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Personal Info</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('showsecurityadm') }}" class="nav-link {{ request()->routeIs('showsecurityadm') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>Security</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <aside class="control-sidebar control-sidebar-light">
  </aside>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
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
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
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
