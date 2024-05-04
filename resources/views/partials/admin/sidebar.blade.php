
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('admin.index') }}" class="text-nowrap logo-img">
          {{-- <img src="{{ url('assets') }}/images/logos/dark-logo.svg" width="180" alt="" /> --}}
          <h4 style="font-weight: bold; text-align: center">ZiWedding</h4>
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>
      <!-- Sidebar navigation-->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Home</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-layout-dashboard"></i>
              </span>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">MENU</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.acara.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-settings-automation"></i>
              </span>
              <span class="hide-menu">Acara</span>
            </a>
          </li>
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">SUB MENU</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('admin/template-undangan*') ? 'active' : '' }}" href="{{ route('admin.template-undangan.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-file-x"></i>
              </span>
              <span class="hide-menu">Template Undangan</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('admin/undangan*') ? 'active' : '' }}" href="{{ route('admin.undangan.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-file-plus"></i>
              </span>
              <span class="hide-menu">Undangan</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('admin/doa*') ? 'active' : '' }}" href="{{ route('admin.doa.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-file-plus"></i>
              </span>
              <span class="hide-menu">Doa</span>
            </a>
          </li>
      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
  </aside>