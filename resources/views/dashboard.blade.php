@extends('layouts.layout')
@section('title', 'Dashboard')

@section('content')
<style>
    .hh{
width:350px;
height:350px;
    }

</style>

<div class="container-fluid">
    <div class="row text-center mb-3 justify-content-center">

        <!-- Total Invoices -->
        <div class="col-lg-3 col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <h6 class="mb-1" style="font-size: 14px;">Total Invoices</h6>
                    <h3 style="font-size: 22px;">{{ $totalInvoices }}</h3>
                </div>
            </div>
        </div>

        <!-- Unpaid Invoices -->
        <div class="col-lg-3 col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <h6 class="mb-1" style="font-size: 14px;">Unpaid Invoices</h6>
                    <h3 class="text-warning" style="font-size: 22px;">₹{{ number_format($unpaidInvoices, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Partially Paid -->
        <div class="col-lg-3 col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <h6 class="mb-1" style="font-size: 14px;">Partially Paid</h6>
                    <h3 class="text-info" style="font-size: 22px;">₹{{ number_format($partialInvoices, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Paid Invoices -->
        <div class="col-lg-3 col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <h6 class="mb-1" style="font-size: 14px;">Paid Invoices</h6>
                    <h3 class="text-success" style="font-size: 22px;">₹{{ number_format($paidInvoices, 2) }}</h3>
                </div>
            </div>
        </div>

    </div>
    <!-- 🔹 Product Stock Pie Chart -->
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="hh border-0 mt-4">
                <div class="card-body text-left">
                    <h5 class="mb-3 fw-bold text-primary">Product Stock Overview</h5>
                    <canvas id="stockPieChart" style="margin-top:15px;"></canvas>
                </div>
            </div>
        </div>

        <!-- 🔹 Clients Bar Chart -->
        <div class="col-lg-6 col-sm-6">
            <div class="hh border-0 mt-4">
                <div class="card-body text-left">
                    <h5 class="mb-3 fw-bold text-primary" style="text-align:center;padding-bottom: 90px">
                        Clients Overview
                    </h5>
                    <canvas id="clientsBarChart" style="margin-top:15px;"></canvas>
                </div>
            </div>
        </div>
    </div>   
 </div>
</div>

<!-- ✅ Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // ===== Product Stock Chart =====
    const totalStock = JSON.parse(`@json($totalStock ?? 0)`);
    const lowStock = JSON.parse(`@json($lowStock ?? 0)`);
    const mediumStock = JSON.parse(`@json($mediumStock ?? 0)`);
    const inStock = JSON.parse(`@json($inStock ?? 0)`);

    const ctx = document.getElementById('stockPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Low Stock', 'Medium Stock', 'In Stock'],
            datasets: [{
                data: [lowStock, mediumStock, inStock],
                backgroundColor: [
                    'rgba(220, 53, 69, 0.9)',
                    'rgba(255, 193, 7, 0.9)',
                    'rgba(40, 167, 69, 0.9)'
                ],
                borderColor: ['#fff', '#fff', '#fff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { font: { size: 14 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });

    // ===== Clients Bar Chart =====
    const monthLabels = JSON.parse(`@json($monthNames ?? [])`);
    const clientCounts = JSON.parse(`@json($clientCounts ?? [])`);

    const backgroundColors = [
        'rgba(57, 4, 143, 0.8)',   // Jan - Purple
        'rgba(255, 99, 132, 0.8)', // Feb - Pink
        'rgba(54, 162, 235, 0.8)', // Mar - Blue
        'rgba(255, 206, 86, 0.8)', // Apr - Yellow
        'rgba(75, 192, 192, 0.8)', // May - Teal
        'rgba(153, 102, 255, 0.8)',// Jun - Violet
        'rgba(255, 159, 64, 0.8)', // Jul - Orange
        'rgba(0, 128, 0, 0.8)',    // Aug - Green
        'rgba(255, 0, 255, 0.8)',  // Sep - Magenta
        'rgba(0, 0, 255, 0.8)',    // Oct - Blue
        'rgba(128, 0, 128, 0.8)',  // Nov - Purple
        'rgba(255, 215, 0, 0.8)'   // Dec - Gold
    ];

    const borderColors = backgroundColors.map(color => color.replace('0.8', '1'));

    const clientCtx = document.getElementById('clientsBarChart').getContext('2d');
    new Chart(clientCtx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: '',
                data: clientCounts,
                backgroundColor: backgroundColors.slice(0, clientCounts.length),
                borderColor: borderColors.slice(0, clientCounts.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#000', font: { size: 12 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { display: false },
                    ticks: { stepSize: 1, color: '#000', font: { size: 13 } }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} Clients`;
                        }
                    }
                }
            }
        }
    });
});
</script>

@endsection
