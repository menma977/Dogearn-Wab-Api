<aside class="main-sidebar sidebar-light-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link navbar-primary">
    <img src="{{ asset('images/Logo.png') }}"
         alt="AdminLTE Logo"
         class="brand-image img-circle elevation-1"
         style="opacity: .8">
    <div class="brand-text font-weight-light"><strong>DOGEARN</strong></div>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="{{ route('user.show', Auth::user()->id) }}" class="d-block text-wrap">{{ \Illuminate\Support\Facades\Auth::user()->email }}</a>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="#" class="nav-link {{ request()->is('penyakit') ? 'active' : '' }}">
            <i class="nav-icon fas fa-heartbeat"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('pin.index') }}" class="nav-link {{ request()->is(['pin', 'pin/*']) ? 'active' : '' }}">
            <i class="nav-icon fab fa-pinterest"></i>
            <p>
              Send PIN
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('binary.index') }}" class="nav-link {{ request()->is(['binary', 'binary/*']) ? 'active' : '' }}">
            <i class="nav-icon fas fa-network-wired"></i>
            <p>
              Network
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
            <i class="nav-icon fas fa-power-off"></i>
            <p>
              Logout
            </p>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </li>
      </ul>
    </nav>
  </div>
</aside>