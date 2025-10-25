<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>App Pegawai</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/stylemaster.css') }}"> -->
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
                <li><a href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'active' : '' }}">Employees</a></li>
                <li><a href="{{ route('departments.index') }}" class="{{ request()->is('departments*') ? 'active' : '' }}">Departments</a></li>
                <li><a href="{{ route('positions.index') }}" class="{{ request()->is('positions*') ? 'active' : '' }}">Positions</a></li>
                <li><a href="{{ route('attendances.index') }}" class="{{ request()->is('attendance*') ? 'active' : '' }}">Attendance</a></li>                
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