@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h5>Dashboard</h5>
</div>

<div class="row">
    <!-- Pharmacy Requests -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pharmacy Requests
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pharmacyRequestsCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-inbox-fill fa-2x text-gray-300"></i> <!-- Bootstrap icon -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registered Pharmacies -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Registered Pharmacies
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedPharmaciesCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-hospital-fill fa-2x text-gray-300"></i> <!-- Bootstrap icon -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Registered Accounts -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Registered Accounts
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRegisteredAccounts }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-fill fa-2x text-gray-300"></i> <!-- Bootstrap icon -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="container-fluid">
    <div class="card mb-4 shadow">
        <div class="card-header">
            <h5 class="card-title">Accounts Overview</h5>
        </div>
        <div class="card-body">
            <canvas id="accountsChart" width="300" height="150"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('accountsChart').getContext('2d');
        var accountsChart = new Chart(ctx, {
            type: 'line', // Line chart to show trends
            data: {
                labels: ['Last Month', 'This Month'],  // Y-axis represents the two months
                datasets: [
                    {
                        label: 'Customers',
                        data: [{{ $lastMonthCustomerCount }}, {{ $thisMonthCustomerCount }}],  // Customer registration counts for the two months
                        borderColor: '#4e73df', // SB Admin 2 primary color
                        backgroundColor: 'rgba(78, 115, 223, 0.05)', // Lightened primary color
                        borderWidth: 2,
                        fill: false,  // No fill under the line
                        tension: 0.4, // Smooth line
                        pointRadius: 5, // Dot size for data points
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#4e73df'
                    },
                    {
                        label: 'Pharmacies',
                        data: [{{ $lastMonthPharmacyCount }}, {{ $thisMonthPharmacyCount }}],  // Pharmacy registration counts for the two months
                        borderColor: '#1cc88a', // SB Admin 2 success color
                        backgroundColor: 'rgba(28, 200, 138, 0.05)', // Lightened success color
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#1cc88a',
                        pointBorderColor: '#1cc88a'
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: false,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: false,
                        }
                    }
                },
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#858796'  // SB Admin 2 muted color
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
