<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>App Pegawai</title>
    <link rel="stylesheet" href="{{ asset('css/stylemaster.css') }}">
</head>
<body>
    <!-- Navbar di atas -->
    <header>
        <div class="navbar-header">
            <h1>App Pegawai</h1>
            <!-- Hamburger button untuk mobile -->
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <nav id="navbar">
            <ul>
                <li><a href="{{ url('/employee') }}" class="{{ request()->is('employee*') ? 'active' : '' }}">Employee</a></li>
                <li><a href="{{ url('/department') }}" class="{{ request()->is('department*') ? 'active' : '' }}">Department</a></li>
                <li><a href="{{ url('/attendance') }}" class="{{ request()->is('attendance*') ? 'active' : '' }}">Attendance</a></li>
                <li><a href="{{ url('/report') }}" class="{{ request()->is('report*') ? 'active' : '' }}">Report</a></li>
                <li><a href="{{ url('/settings') }}" class="{{ request()->is('settings*') ? 'active' : '' }}">Settings</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} App Pegawai. All rights reserved.</p>
    </footer>

    <script>
        // Toggle menu untuk mobile
        const menuToggle = document.getElementById('menuToggle');
        const navbar = document.getElementById('navbar');

        menuToggle.addEventListener('click', function() {
            menuToggle.classList.toggle('active');
            navbar.classList.toggle('active');
        });
    </script>
</body>
</html>