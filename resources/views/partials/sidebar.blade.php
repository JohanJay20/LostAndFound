<nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
          <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('dashboard') }}">
    <i class="mdi mdi-grid-large menu-icon"></i>
    <span class="menu-title">Dashboard</span>
  </a>
</li>

          
          
<li class="nav-item nav-category">UI Elements</li>
        
            
            
            <li class="nav-item {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('tenants.index') }}">
    <i class="menu-icon fa fa-group"></i>
    <span class="menu-title">Tenants</span>
  </a>
</li>

          </ul>
        </nav>
