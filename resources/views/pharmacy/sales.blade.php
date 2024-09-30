@extends('layouts.pharmacy')

@section('title', 'Sales')

@section('content')
<div class="container mt-4" id="sales-page">
    <h5>Sales</h5><hr>
    <br>
    <div class="row justify-content-center mb-4">
        <!-- Daily Sales Total Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #e1e6db;">
                    <span class="text">Daily Sales Total</span>
                    <i class="bi bi-cash-coin fs-3"></i>
                </div>
                <div class="card-body text-center">
                    <div class="h6 mb-1 text-gray-800">{{ $currentDay }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Total: Php {{ number_format($dailySalesTotal, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Weekly Sales Total Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #e1e6db;">
                    <span class="text">Weekly Sales Total</span>
                    <i class="bi bi-cash-coin fs-3"></i>
                </div>
                <div class="card-body text-center">
                    <div class="h6 mb-1 text-gray-800">Week {{ $currentWeek }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Total: Php {{ number_format($weeklySalesTotal, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Monthly Sales Total Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #e1e6db;">
                    <span class="text">Monthly Sales Total</span>
                    <i class="bi bi-cash-coin fs-3"></i>
                </div>
                <div class="card-body text-center">
                    <div class="h6 mb-1 text-gray-800">{{ $currentMonth }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Total: Php {{ number_format($monthlySalesTotal, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Line Chart for Monthly Profit -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Monthly Profit by Product</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="monthlyProfitChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bar Chart for Top Products -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top Products</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Table for Product Sales -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-secondary">Product Sales Summary</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="salesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Brand Name</th> <!-- Changed from Product Name to Brand Name -->
                        <th>Daily Sales</th>
                        <th>Weekly Sales</th>
                        <th>Monthly Sales</th>
                        <th>Monthly Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productSales as $sale)
                    <tr>
                        <td>{{ $sale->brand_name }}</td> <!-- Updated to brand_name -->
                        <td>{{ number_format($dailyProductSales[$sale->brand_name] ?? 0, 2) }}</td>
                        <td>{{ number_format($weeklyProductSales[$sale->brand_name] ?? 0, 2) }}</td>
                        <td>{{ number_format($monthlyProductSales[$sale->brand_name] ?? 0, 2) }}</td>
                        <td>{{ number_format($sale->monthly_profit ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ensure Chart.js is loaded correctly
        if (typeof Chart !== 'undefined') {
            // Initialize Monthly Profit Chart
            var profitCtx = document.getElementById('monthlyProfitChart').getContext('2d');
            new Chart(profitCtx, {
                type: 'line',
                data: {
                    labels: @json($profitChartLabels),
                    datasets: [{
                        label: 'Monthly Profit',
                        data: @json($profitChartData),
                        borderColor: '#0eba9c', 
                        backgroundColor: 'rgba(14, 186, 156, 0.1)', 
                        borderWidth: 2,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#0eba9c',  
                        pointBorderColor: '#0eba9c',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: '#0ba089', 
                        pointHoverBorderColor: '#0ba089',
                        pointHitRadius: 10,
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        },
                        y: {
                            ticks: {
                                beginAtZero: true,
                                padding: 10,
                                callback: function(value, index, values) {
                                    return 'P ' + value; 
                                }
                            },
                            grid: {
                                color: "rgba(234, 236, 244, 1)", 
                                zeroLineColor: "rgba(234, 236, 244, 1)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false 
                        },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleColor: '#6e707e',
                            titleMarginBottom: 10,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10
                        }
                    }
                }
            });

            // Initialize Top Products Chart
            var topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
            new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: @json($topProductsChartLabels),
                    datasets: [{
                        label: 'Total Quantity Sold',
                        data: @json($topProductsChartData),
                        backgroundColor: '#7daaa5', 
                        hoverBackgroundColor: '#659f97',  
                        borderColor: '#7daaa5',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,  
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 6
                            }
                        },
                        y: {
                            ticks: {
                                beginAtZero: true,
                                padding: 10,
                                callback: function(value, index, values) {
                                    return 'P ' + value;
                                }
                            },
                            grid: {
                                color: "rgba(234, 236, 244, 1)",  
                                zeroLineColor: "rgba(234, 236, 244, 1)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false 
                        },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleColor: '#6e707e',
                            titleMarginBottom: 10,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
