<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: "Poppins", sans-serif;
        }

    
        .sidebar {
            background-color: #465260;
        }

        .sidebar .nav-link {
            color: #b0bdba; 
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
        }

        .sidebar .nav-link.active {
            background-color: #556f77; 
            color: #ffffff; 
        }

        .sidebar .sidebar-brand {
            background-color: #556f77; /
            color: #ffffff;
        }

        .sidebar .sidebar-brand:hover {
            color: #b0bdba;
        }

        .sidebar-divider {
            border-top: 1px solid #b0bdba; 
        }

        .profile-name {
            color: #b0bdba;
            font-weight: bold;
        }

        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .profile-section img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        .profile-section .profile-name {
            margin-top: 10px;
        }

        .btn-info {
            background-color: #556f77; 
            border-color: #556f77;
        }

        .btn-info:hover {
            background-color: #465260; 
        }

        .btn-info:focus {
            box-shadow: 0 0 0 0.2rem rgba(70, 82, 96, 0.5); 
        }
        .table th {
            font-size: 15px;
        }

        .table td {
            font-size: 14px;
        }
    </style>
</head>
<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Profile Section -->
            <div class="profile-section">
                <img src="{{ asset('img/undraw_profile_3.svg') }}" alt="Profile Picture"> <!-- Optional profile picture -->
                <div class="profile-name">
                    {{ Auth::user()->name }}
                </div>
            </div>
            <hr class="sidebar-divider my-0">

            <!-- Navigation Links -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pharmacyRequests') }}">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Pharmacy Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.userManagement') }}">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>User Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('main_products.index') }}">
                    <i class="bi bi-box"></i>
                    <span>Products Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.locations') }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Location Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.settings') }}">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>

            <!-- Logout Button -->
            <li class="nav-item">
                <button class="btn btn-info mt-3" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div class="container-fluid">
            <div id="content">
                @yield('content')
            </div>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>
