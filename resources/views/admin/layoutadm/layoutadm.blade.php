<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Management Organization</title>

  @yield('head')

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="{{ asset('template/https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback') }}">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="background-color: #e2e4fb;">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand" style="background-color: #e2e4fb;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color: #3200af;"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Profile Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" style="font-size: 25px; color: #7773d4;" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          @if(session('profile_picture'))
            <img src="{{ session('profile_picture') }}" class="rounded-circle" style="width: 30px; height: 30px;">
          @else
            <i class="fas fa-user-circle rounded-circle" style="width: 30px; height: 30px; color: #888888;"></i>
          @endif
          <span class="ml-02" style="font-weight: bold; color: #3200af; font-size: 18px;">{{ session('username') }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('personaladm') }}">Settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" id="logout">Logout</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #e1e5f8;">
    <!-- Sidebar -->
    <div class="sidebar my-5 sidebar-collapse">
      <!-- Sidebar Menu -->
      <nav class="">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('dashboardadm') }}" class="nav-link active" style="background-color: #7773d4;">
              <i class="nav-icon fas fa-tachometer-alt" style="color: #eef5f8;"></i>
              <p style="color: #ffffff;">Dashboard</p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link" style="background-color: #7773d4;">
              <i class="nav-icon fas fa-users" style="color: #eef5f8;"></i>
              <p style="color: #ffffff;">Administrator<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('showuserdata') }}" class="nav-link">
                  <i class="fas fa-user nav-icon" style="color: #7773d4;"></i>
                  <p>User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('showuserrole') }}" class="nav-link">
                  <i class="fas fa-user-tag nav-icon" style="color: #7773d4;"></i>
                  <p>User Role</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #e1e5f8;">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-light">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('template/dist/js/adminlte.js') }}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{ asset('template/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('template/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('template/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('template/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ asset('template/dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('template/dist/js/pages/dashboard2.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
  $(document).ready(function () {
    // Munculkan dropdown saat ikon profil diklik
    $('.dropdown-toggle').dropdown('toggle');
  });
</script>

<script>
  $(document).ready(function() {
    // Tangani klik pada tombol logout
    $('#logout').click(function() {
      // Tampilkan SweetAlert konfirmasi logout
      Swal.fire({
        title: 'Logout',
        text: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
      }).then((result) => {
        // Jika pengguna menekan tombol Ya, logout
        if (result.isConfirmed) {
          // Redirect ke fungsi logout setelah pengguna menekan tombol Ya
          window.location.href = "{{ route('logout') }}";
        }
      });
    });
  });
</script>
<script>
  // Tambahkan kelas 'sidebar-collapse' ke elemen sidebar saat halaman dimuat
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('.sidebar').classList.add('sidebar-collapse');
  });
</script>

@yield('script')
</body>
</html>
