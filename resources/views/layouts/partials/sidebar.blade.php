<div class="d-flex flex-column flex-shrink-0 p-0 sidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center mb-4" href="#">
        <div class="sidebar-brand-icon">
            <i class="bi bi-layers"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PharmaGIS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Navigation Links -->
    <ul class="nav nav-pills flex-column mb-auto">
        @if(Auth::user()->pharmacy->is_approved) <!-- Check if pharmacy is approved -->
            <li class="nav-item">
                <a href="{{ route('pharmacy.dashboard') }}" class="nav-link {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            @if (Auth::user()->pharmacy->sub_role === 'owner')
                <li class="nav-item">
                    <a href="{{ route('pharmacy.products') }}" class="nav-link {{ request()->routeIs('pharmacy.products') ? 'active' : '' }}">
                        <i class="bi bi-box"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pharmacy.inventory') }}" class="nav-link {{ request()->routeIs('pharmacy.inventory') ? 'active' : '' }}">
                        <i class="bi bi-archive"></i> Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('pharmacy.categories.index') ? 'active' : '' }}">
                        <i class="bi bi-list"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pharmacy.sales') }}" class="nav-link {{ request()->routeIs('pharmacy.sales') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i> Sales
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pharmacy.products-expiry') }}" class="nav-link {{ request()->routeIs('pharmacy.products-expiry') ? 'active' : '' }}">
                        <i class="bi bi-calendar"></i> Expiry
                    </a>
                </li>
            @elseif (Auth::user()->pharmacy->sub_role === 'employee')
                <li class="nav-item">
                    <a href="{{ route('pharmacy.employee.products') }}" class="nav-link {{ request()->routeIs('pharmacy.employee.products') ? 'active' : '' }}">
                        <i class="bi bi-box"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pharmacy.emp.products-expiry') }}" class="nav-link {{ request()->routeIs('pharmacy.emp.products-expiry') ? 'active' : '' }}">
                        <i class="bi bi-calendar"></i> Expiry
                    </a>
                </li>
            @endif
        @else
        <li class="nav-item">
    <a href="{{ route('pharmacy.upload_documents_handler') }}" class="nav-link {{ request()->routeIs('pharmacy.upload_documents_handler') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-upload"></i> Documents
    </a>
</li>
        @endif
    </ul>
    <hr class="sidebar-divider">
</div>

<!-- Sidebar CSS Styling -->
<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #556f77; /* Sidebar color */
        color: #ffffff;
        padding-top: 20px;
        box-shadow: 4px 0 8px rgba(0, 0, 0, 0.1);
    }

    .sidebar .nav-link {
        color: #ffffff;
        padding: 12px 15px;
        display: flex;
        align-items: center;
        font-size: 1rem;
        transition: background-color 0.2s;
    }

    .sidebar .nav-link i {
        margin-right: 10px;
    }

    .sidebar .nav-link.active, 
    .sidebar .nav-link:hover {
        background-color: #404C58;
        color: #e1e6db;
    }

    .sidebar hr {
        border-color: rgba(255, 255, 255, 0.2);
    }

    .sidebar-brand {
        color: #ffffff;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .sidebar-brand-icon {
        font-size: 1.5rem;
        color: #ffffff;
    }
</style>
