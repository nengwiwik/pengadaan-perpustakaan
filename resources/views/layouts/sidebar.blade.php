<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
    <div class="sidebar-brand-icon">
      <i class="fas fa-building"></i>
    </div>
    <div class="sidebar-brand-text mx-3">UNDIRA <sup></sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="/">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  @role('Super Admin')
  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Super Admin
  </div>

  <li class="nav-item {{ $type_menu === 'user' ? 'active' : '' }}">
    <a class="nav-link {{ $type_menu === 'user' ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
      data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
      <i class="fas fa-fw fa-user-plus"></i>
      <span>Users</span>
    </a>
    <div id="collapseUsers" class="collapse {{ $type_menu === 'user' ? 'show' : '' }}"
      aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item @if (\Request::is('admin/users/prodi')) active @endif"
          href="{{ route('prodi_users') }}">Admin Prodi</a>
        <a class="collapse-item @if (\Request::is('admin/users/publisher')) active @endif"
          href="{{ route('publisher_users') }}">Admin Publisher</a>
        <a class="collapse-item @if (\Request::is('admin/users/inactive')) active @endif"
          href="{{ route('inactive_users') }}">Inactive Users</a>
      </div>
    </div>
  </li>

  {{-- <li class="nav-item">
    <a class="nav-link" href="{{ route('roles') }}">
      <i class="fas fa-fw fa-user-plus"></i>
      <span>Roles</span></a>
  </li> --}}

  {{-- <li class="nav-item">
    <a class="nav-link" href="{{ route('permissions') }}">
      <i class="fas fa-fw fa-user-shield"></i>
      <span>Permissions</span></a>
  </li> --}}

  <li class="nav-item">
    <a class="nav-link" href="{{ route('campuses') }}">
      <i class="fas fa-fw fa-building"></i>
      <span>Campus</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('majors') }}">
      <i class="fas fa-fw fa-graduation-cap"></i>
      <span>Majors</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('publisher') }}">
      <i class="fas fa-fw fa-book"></i>
      <span>Publisher</span></a>
  </li>

  {{-- <li class="nav-item">
    <a class="nav-link" href="{{ route('procurements') }}">
      <i class="fas fa-fw fa-shopping-cart"></i>
      <span>Procurements</span></a>
  </li> --}}

  <li class="nav-item {{ $type_menu === 'procurements' ? 'active' : '' }}">
    <a class="nav-link {{ $type_menu === 'procurements' ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
      data-target="#collapseProcurements" aria-expanded="true" aria-controls="collapseProcurements">
      <i class="fas fa-fw fa-shopping-cart"></i>
      <span>Procurements</span>
    </a>
    <div id="collapseProcurements" class="collapse {{ $type_menu === 'procurements' ? 'show' : '' }}"
      aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item @if (\Request::is('admin/procurements/new')) active @endif"
          href="{{ route('procurements.new') }}">New Procurements</a>
        <a class="collapse-item @if (\Request::is('admin/procurements/active')) active @endif"
          href="{{ route('procurements.active') }}">Active Procurements</a>
        <a class="collapse-item @if (\Request::is('admin/users/inactive')) active @endif"
          href="{{ route('inactive_users') }}">Archived Procurements</a>
      </div>
    </div>
  </li>


  @endrole

  @role('Penerbit')
  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Penerbit
  </div>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('penerbit.invoices') }}">
      <i class="fas fa-fw fa-book"></i>
      <span>New Invoices</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('penerbit.invoices.ongoing') }}">
      <i class="fas fa-fw fa-book"></i>
      <span>On-Going Invoices</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('penerbit.invoices.verified') }}">
      <i class="fas fa-fw fa-book"></i>
      <span>Verified Invoices</span></a>
  </li>

  @endrole

  @role('Admin Prodi')
  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Admin Prodi
  </div>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('penerbit.invoices') }}">
      <i class="fas fa-fw fa-book"></i>
      <span>Active Procurements</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('penerbit.invoices') }}">
      <i class="fas fa-fw fa-book"></i>
      <span>Archived Procurements</span></a>
  </li>

  @endrole

  <!-- Nav Item - Pages Collapse Menu -->
  {{-- <li class="nav-item active">
    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
      aria-controls="collapsePages">
      <i class="fas fa-fw fa-folder"></i>
      <span>Pages</span>
    </a>
    <div id="collapsePages" class="collapse show" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Login Screens:</h6>
        <a class="collapse-item" href="login.html">Login</a>
        <a class="collapse-item" href="register.html">Register</a>
        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
        <div class="collapse-divider"></div>
        <h6 class="collapse-header">Other Pages:</h6>
        <a class="collapse-item" href="404.html">404 Page</a>
        <a class="collapse-item active" href="blank.html">Blank Page</a>
      </div>
    </div>
  </li> --}}

  <!-- Nav Item - Charts -->
  {{-- <li class="nav-item">
    <a class="nav-link" href="charts.html">
      <i class="fas fa-fw fa-chart-area"></i>
      <span>Charts</span></a>
  </li> --}}


  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->
