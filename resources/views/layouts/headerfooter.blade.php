<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tracking</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrapicons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>
<body>
    @php
        $loggedInAdminAndDriver = session('admin_and_driver_id')
            ? \App\Models\Tenant\AdminAndDriver::find(session('admin_and_driver_id'))
            : null;
    @endphp
    <div class="top-navbar">
        <a href="{{ route('parents.index') }}" class="navbar-brand-title">TRACKING</a>
        <div class="nav-menu-wrapper">
            <div class="nav-dropdown-container">
                <a href="#" class="nav-dropdown-trigger">Masters <i class="bi bi-caret-down-fill"></i></a>
                <ul class="nav-dropdown-menu">
                    <li><a href="{{ route('parents.index') }}">Parents</a></li>
                    <li><a href="{{ route('students.index') }}">Students</a></li>
                    <li><a href="{{ route('class-sections.index') }}">Class / Sections</a></li>
                    <li><a href="{{ route('vehicles.index') }}">Vehicles</a></li>
                    <li><a href="{{ route('vehicle-routes.index') }}">Routes</a></li>
                    <li><a href="{{ route('stops.index') }}">Stops</a></li>
                </ul>
            </div>
            <div class="nav-dropdown-container">
                <a href="#" class="nav-dropdown-trigger">Assignments <i class="bi bi-caret-down-fill"></i></a>
                <ul class="nav-dropdown-menu">
                    <li><a href="{{ route('student-route-assignments.index') }}">Student Routes</a></li>
                    <li><a href="{{ route('active-trips.index') }}">Active Trips</a></li>
                </ul>
            </div>
            <div class="nav-dropdown-container">
                <a href="#" class="nav-dropdown-trigger">Tracking <i class="bi bi-caret-down-fill"></i></a>
                <ul class="nav-dropdown-menu">
                    <li><a href="{{ route('live-vehicle-locations.index') }}">Live Locations</a></li>
                    <li><a href="{{ route('vehicle-location-history.index') }}">Location History</a></li>
                </ul>
            </div>
            <div class="nav-dropdown-container">
                <a href="#" class="nav-dropdown-trigger">Admin <i class="bi bi-caret-down-fill"></i></a>
                <ul class="nav-dropdown-menu nav-dropdown-menu-right">
                    <li><a href="{{ route('admin-and-drivers.index') }}">Admin & Drivers</a></li>
                </ul>
            </div>
        </div>
        <div class="user-profile-block">
            <div style="width: 26px; height: 26px; background-color: #fff; color: var(--header-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.75rem;">
                {{ $loggedInAdminAndDriver ? strtoupper(substr($loggedInAdminAndDriver->full_name, 0, 1)) : 'U' }}
            </div>
            <span>{{ $loggedInAdminAndDriver?->full_name ?: 'User' }}</span>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-light">Logout</button>
            </form>
        </div>
    </div>

    <div class="page-content-body">
        @yield('content')
    </div>

    <div class="system-footer-bar">
        <span>&copy; 2026 <strong class="text-primary text-decoration-none">School Bus Tracking</strong></span>
        <span>All Rights Reserved</span>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/searchable-dropdown.js') }}"></script>
    @stack('scripts')
</body>
</html>
