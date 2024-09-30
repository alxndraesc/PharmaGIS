<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/litera/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (necessary for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body, html {
            font-family: "Poppins", sans-serif;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Hide overflow for the body */
        }

        .container-fluid {
            display: flex;
            height: 100vh;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto; /* Enable scrolling only in content */
        }

        .navbar {
            height: 56px; /* Thin navbar */
            background-color: #f8f9fa; /* Off-white */
        }

        .table th {
            font-size: 15px;
        }

        .table td {
            font-size: 14px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 1rem;
        }

        .img-profile {
    width: 40px; /* Adjust width as needed */
    height: 40px; /* Adjust height as needed */
    font-size: 10px; /* You can keep this or adjust it */
}
    </style>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#product').select2({
                placeholder: 'Search for a product',
                allowClear: true
            });
        });
    </script>
</head>
<body id="page-top">
    <div id="wrapper">
        @include('layouts.partials.sidebar') <!-- Include sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-light">
    <ul class="navbar-nav ms-auto">
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'User' }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                @if (Auth::user()->pharmacy->is_approved)
                    @if (Auth::user()->pharmacy->sub_role === 'owner')
                        <li>
                            <a class="dropdown-item" href="{{ route('pharmacy.account') }}">
                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Account
                            </a>
                        </li>
                    @endif

                    <!-- Link to Select Role Page -->
                    <li>
                        <a class="dropdown-item" href="{{ route('pharmacy.selectRole') }}">
                            <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Select Role
                        </a>
                    </li>
                @endif

                <!-- Divider -->
                <li><hr class="dropdown-divider"></li>

                <!-- Link to Logout -->
                <li>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout
                    </a>
                    <!-- Laravel Logout Form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

                <!-- End of Topbar -->

                <!-- Main Content -->
                <div class="container-fluid">
                    @yield('content') <!-- Content goes here -->
                </div>
            </div>
        </div>
    </div>

</body>
</html>
