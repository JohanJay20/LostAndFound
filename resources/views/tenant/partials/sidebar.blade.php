<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Dashboard Link - Visible only for admin users -->
        @if (tenant() && tenant('plan') !== 'Basic' && Auth::user()->role == 'admin') 
        <!-- Check if the tenant's plan is not basic and the user is an admin -->
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @endif

        <!-- Staff Link - Visible only for admin users -->
        <li class="nav-item nav-category">UI Elements</li>
        @if (tenant() && tenant('plan') !== 'Basic' && Auth::user()->role == 'admin') 
        <!-- Check if the tenant's plan is not basic and the user is an admin -->
        <li class="nav-item {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('staff.index') }}">
                <i class="menu-icon fa fa-group"></i>
                <span class="menu-title">Staff</span>
            </a>
        </li>
        @endif
        
        <li class="nav-item {{ request()->routeIs('lostandfound.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('lostandfound.index') }}">
                <i class="menu-icon fa fa-archive"></i>
                <span class="menu-title">LostAndFound</span>
            </a>
        </li>

        <!-- Reports Link - Visible only for admin users -->
        @if (tenant() && tenant('plan') !== 'Basic' && Auth::user()->role == 'admin') 
        <!-- Check if the tenant's plan is not basic and the user is an admin -->
        <li class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('reports.index') }}">
                <i class="menu-icon fa fa-bar-chart-o"></i>
                <span class="menu-title">Reports</span>
            </a>
        </li>
        @endif
        <!-- Customize Link - Visible only for admin users -->
        @if (tenant() && tenant('plan') !== 'Basic' && Auth::user()->role == 'admin') 
        <!-- Check if the tenant's plan is not basic and the user is an admin -->
        <li class="nav-item {{ request()->routeIs('customize.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('customize.index') }}">
                <i class="menu-icon fa fa-edit"></i>
                <span class="menu-title">Customize</span>
            </a>
        </li>
        @endif
             <li class="nav-item">
    <div class="nav-link " >
        <i class="menu-icon fa fa-edit"></i>
        <span class="menu-title">New Features</span>
    </div>
</li>
    
    </ul>
</nav>
