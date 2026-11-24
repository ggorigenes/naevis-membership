<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nævis Membership System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --color-white: #FFFFFF;
            --color-bg: #EDF3FB;
            --color-border-soft: #CBE3EF;
            --color-primary: #5AA8D6;
            --color-primary-hover: #4893be;
            --color-navy: #3A4163;
            --color-navy-hover: #2c324e;
        }

        /* 1. Global Page Background & Typography */
        body {
            background-color: var(--color-bg);
            color: var(--color-navy);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--color-navy);
            font-weight: 700;
        }

        /* 2. Top Navigation Bar & High-Contrast Active Route Highlight */
        .navbar {
            background-color: var(--color-navy) !important;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            box-shadow: 0 4px 12px rgba(58, 65, 99, 0.15);
        }

        .navbar .navbar-brand {
            color: var(--color-white) !important;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .navbar .nav-link {
            color: var(--color-border-soft) !important;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .navbar .nav-link:hover {
            color: var(--color-white) !important;
            background-color: rgba(255, 255, 255, 0.08);
        }

        /* Active Link: Pops brightly against the dark navy bar */
        .navbar .nav-link.active {
            color: var(--color-white) !important;
            background-color: var(--color-primary) !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 8px rgba(90, 168, 214, 0.4);
        }

        /* 3. Cards & Containers */
        .card {
            background-color: var(--color-white);
            border: 1px solid var(--color-border-soft);
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(58, 65, 99, 0.04);
        }

        .card-header {
            background-color: var(--color-bg);
            border-bottom: 1px solid var(--color-border-soft);
            color: var(--color-navy);
            font-weight: 700;
            padding: 1rem 1.25rem;
        }

        /* 4. Modern Table Header & Hover States */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }

        .table thead th,
        .table-dark thead th,
        thead.table-dark th {
            background-color: var(--color-navy) !important;
            color: var(--color-white) !important;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none !important;
            padding: 12px 16px;
            vertical-align: middle;
        }

        .table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--color-border-soft);
            color: var(--color-navy);
        }

        .table tbody tr:hover {
            background-color: #f5f8fd;
        }

        /* 5. Buttons */
        .btn-primary {
            background-color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: var(--color-white) !important;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(90, 168, 214, 0.25);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--color-primary-hover) !important;
            border-color: var(--color-primary-hover) !important;
        }

        .btn-outline-primary {
            color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background-color: var(--color-primary) !important;
            color: var(--color-white) !important;
        }

        /* 6. Form Controls & Focus Rings */
        .form-control,
        .form-select {
            border: 1px solid var(--color-border-soft);
            color: var(--color-navy);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.25rem rgba(90, 168, 214, 0.25);
        }

        /* 7. Badges & Accents */
        .badge.bg-light {
            background-color: var(--color-bg) !important;
            color: var(--color-navy) !important;
            border: 1px solid var(--color-border-soft) !important;
        }

        .text-primary {
            color: var(--color-primary) !important;
        }

        .bg-primary {
            background-color: var(--color-primary) !important;
        }
    </style>    
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">Nævis</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">Members</a>
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a>
                <a class="nav-link {{ request()->routeIs('levels.*') ? 'active' : '' }}" href="{{ route('levels.index') }}">Levels</a>
                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Transactions</a>
                <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">Payments</a>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="d-flex">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>

</html>