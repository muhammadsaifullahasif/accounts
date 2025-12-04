<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image" style="opacity: .8; height: 100%;">
        {{-- <span class="brand-text font-weight-light">AdminLTE 3</span> --}}
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent nav-flat flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('companies.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Companies
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('companies.index') }}" class="nav-link {{ request()->routeIs('companies.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Companies</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('companies.create') }}" class="nav-link {{ request()->routeIs('companies.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Company</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->routeIs('audit-reports.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('audit-reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-contract"></i>
                        <p>
                            Audit Report
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('audit-reports.index') }}" class="nav-link {{ request()->routeIs('audit-reports.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Audit Reports</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('audit-reports.create') }}" class="nav-link {{ request()->routeIs('audit-reports.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Audit Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->routeIs('accounting-policy.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('accounting-policy.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Accounting Policies
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('accounting-policy.index') }}" class="nav-link {{ request()->routeIs('accounting-policy.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Accounting Policies</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('accounting-policy.create') }}" class="nav-link {{ request()->routeIs('accounting-policy.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Accounting Policy</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                        @csrf
                    </form>
                    <a href="{{ route('logout') }}" class="nav-link text-danger" onclick="event.preventDefault();document.getElementById('sidebar-logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Signout
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-header">EXAMPLES</li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>