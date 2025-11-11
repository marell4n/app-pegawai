<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Pegawai</title>
    <link rel="stylesheet" href="{{ asset('css/stylemaster.css') }}">
</head>
<body>
    <!-- Navbar di atas (Fixed) -->
    <header class="header-fixed">
        <div class="navbar-header">
            <h1><a href="{{ route('dashboard') }}">App Pegawai</a></h1>
            <!-- Hamburger button untuk mobile -->
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <nav id="navbar">
            <ul>
                <li><a href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'active' : '' }}">Employees</a></li>
                <li><a href="{{ route('departments.index') }}" class="{{ request()->is('departments*') ? 'active' : '' }}">Departments</a></li>
                <li><a href="{{ route('positions.index') }}" class="{{ request()->is('positions*') ? 'active' : '' }}">Positions</a></li>
                <li><a href="{{ route('attendances.index') }}" class="{{ request()->is('attendances*') ? 'active' : '' }}">Attendance</a></li>
                <li><a href="{{ route('performance-reviews.index') }}" class="{{ request()->is('performance-reviews*') ? 'active' : '' }}">Reviews</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content dengan padding -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer (Fixed) -->
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