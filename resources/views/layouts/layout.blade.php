<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Billing System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar */
        .sidebar {
            width: 230px;
            background: #E8EDF2;
            color: #333;
            min-height: 100vh;
            transition: all 0.3s;
            border-right: 1px solid #ccc;
        }

        /* Sidebar logo */
        .sidebar .logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .sidebar h4 {
            color: #0b5ed7;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Sidebar links */
        .sidebar a {
            text-decoration: none;
            display: block;
            padding: 12px 18px;
            margin-bottom: 6px;
            border-radius: 10px;
            font-weight: 500;
            color: #333;
            position: relative;
            transition: all 0.3s ease;
        }

        /* Hover effect */
        .sidebar a:hover {
            background: #0a53be;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Active link */
        .sidebar a.active {
            background-color: #0b5ed7;
            color: #fff;
            font-weight: 600;
        }

        /* COLLAPSE SUBMENU LINKS — UPDATED COLORS */
        .sidebar .collapse a {
            display: flex;
            align-items: center;
            padding-left: 25px;
            font-weight: 500;
            border-radius: 8px;
            background-color: rgba(11, 94, 215, 0.10);
            margin-bottom: 4px;
            transition: all 0.3s ease;
            color: #333;
        }

        .sidebar .collapse a:hover {
            background-color: #0b5ed7 !important;
            color: #fff !important;
            transform: scale(1.03);
        }

        .sidebar .collapse a i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* Icons */
        .sidebar a i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .flex-fill {
            background-color: #f8f9fa;
            padding: 30px;
            transition: all 0.3s;
        }

        .toast-body {
            font-weight: 600;
            font-size: 0.95rem;
        }
    </style>
</head>

<body>
    <div class="d-flex">

        <!-- Sidebar -->
        <div class="sidebar p-3">
            <div class="text-center logo mb-4">
                @php
                $logo = \App\Models\User::whereNotNull('logo')->value('logo');
                @endphp

                @if($logo)
                <img src="{{ asset($logo) }}" alt="Logo">
                @endif
                <h4>Billing App</h4>
            </div>

            <!-- Main Links -->
            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Dashboard
            </a>
            <!--<a href="{{ route('invoices.index') }}" class="{{ request()->is('invoices*') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i> Invoices
        </a>-->
            <a href="{{ route('clients.index') }}" class="{{ request()->is('clients*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Clients
            </a>
            <div>
    <a class="d-flex justify-content-between align-items-center {{ request()->is('products/categories*') || request()->is('products/subcategories*') || request()->is('products/sizes*') || request()->is('products/brands*') ? 'active' : '' }}"
       data-bs-toggle="collapse" href="#productSubMenu" role="button">
        <span><i class="bi bi-box2-fill"></i>Item categories</span>
        <i class="bi bi-chevron-down"></i>
    </a>

    <div class="collapse {{ request()->is('products/categories*') || request()->is('products/subcategories*') || request()->is('products/sizes*') || request()->is('products/brands*') ? 'show' : '' }}" id="productSubMenu">
        
       <a href="{{ route('products.categories') }}" class="{{ request()->is('products/categories') ? 'active' : '' }}">
    <i class="bi bi-list-task"></i> Categories
</a>

<a href="{{ route('products.subcategories') }}" class="{{ request()->is('products/subcategories') ? 'active' : '' }}">
    <i class="bi bi-tag-fill"></i> Subcategories
</a>

<a href="{{ route('products.sizes') }}" class="{{ request()->is('products/sizes') ? 'active' : '' }}">
    <i class="bi bi-arrows-expand"></i> kg
</a>

<a href="{{ route('products.brands') }}" class="{{ request()->is('products/brands') ? 'active' : '' }}">
    <i class="bi bi-award-fill"></i> Brands
</a>

    </div>
</div>

            <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
                <i class="bi bi-box2-fill"></i> Item
            </a>
            
            <!-- PURCHASES (Only Vendor kept — Purchase Order removed) -->
            <div>
                <a class="d-flex justify-content-between align-items-center {{ request()->is('purchases*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#purchasesMenu" role="button"
                    aria-expanded="{{ request()->is('purchases*') ? 'true' : 'false' }}">
                    <span><i class="bi bi-cart-fill"></i> Purchases</span>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <div class="collapse {{ request()->is('purchases*') ? 'show' : '' }}" id="purchasesMenu">

                    <!-- REMOVED PURCHASE ORDER MENU -->

                    <a href="{{ route('purchases.vendors.index') }}" class="{{ request()->is('purchases/vendors*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i> Suppliers
                    </a>
                    <!-- PURCHASE INVOICE SUBMODULE (NEW) -->
                    <a href="{{ route('purchase.invoice.index') }}" class="{{ request()->is('purchases/invoices*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i> Purchase Invoices
                    </a>

                </div>
            </div>

            <!-- SALES -->
            <div>
                <a class="d-flex justify-content-between align-items-center {{ request()->is('sales*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#salesMenu" role="button"
                    aria-expanded="{{ request()->is('sales*') ? 'true' : 'false' }}">
                    <span><i class="bi bi-receipt-cutoff"></i> Sales</span>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <div class="collapse {{ request()->is('sales*') ? 'show' : '' }}" id="salesMenu">

                    <!-- INVOICE SUBMENU -->
                    <a href="{{ route('invoices.index') }}" class="{{ request()->is('sales/invoices*') || request()->is('invoices*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text-fill"></i> Invoices
                    </a>

                </div>
            </div>
            <div>
    <a class="d-flex justify-content-between align-items-center {{ request()->is('reports*') ? 'active' : '' }}"
       data-bs-toggle="collapse" href="#reportsMenu" role="button"
       aria-expanded="{{ request()->is('reports*') ? 'true' : 'false' }}"
       aria-controls="reportsMenu">
        <span><i class="bi bi-file-earmark-bar-graph-fill"></i> Reports</span>
        <i class="bi bi-chevron-down"></i>
    </a>

    <div class="collapse {{ request()->is('reports*') ? 'show' : '' }}" id="reportsMenu">
        <ul class="list-unstyled ps-4">

            <li>
                <a href="{{ route('reports.sales') }}"
                   class="{{ request()->is('reports/sales') ? 'active' : '' }}">
                    📊 Sales Report
                </a>
            </li>

            <li>
                <a href="{{ route('reports.profitloss') }}"
                   class="{{ request()->is('reports/profit-loss') ? 'active' : '' }}">
                    💰 Profit & Loss Report
                </a>
            </li>

        </ul>
    </div>
</div>

            <a href="{{ route('settings.index') }}" class="{{ request()->is('settings*') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> Settings
            </a>
            <hr style="border-top: 1px solid #ccc;">
            <a href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="flex-fill p-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="toastSuccess" class="toast text-bg-success border-0">
            <div class="d-flex">
                <div class="toast-body fw-semibold">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="toastError" class="toast text-bg-danger border-0">
            <div class="d-flex">
                <div class="toast-body fw-semibold">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('toastSuccess')) {
                new bootstrap.Toast(document.getElementById('toastSuccess'), {
                    delay: 2000
                }).show();
            }
            if (document.getElementById('toastError')) {
                new bootstrap.Toast(document.getElementById('toastError'), {
                    delay: 3000
                }).show();
            }
        });
    </script>

</body>

</html>