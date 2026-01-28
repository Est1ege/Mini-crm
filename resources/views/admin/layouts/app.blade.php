<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Mini CRM</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }

        .navbar {
            background: #1a1a2e;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .navbar-brand {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            text-decoration: none;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .navbar-nav a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .navbar-nav a:hover,
        .navbar-nav a.active {
            color: #fff;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .navbar-user span {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 28px;
            color: #1a1a1a;
            font-weight: 600;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #eee;
            background: #fafafa;
        }

        .card-body {
            padding: 24px;
        }

        .alert {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            font-size: 14px;
            color: #333;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-new { background: #e3f2fd; color: #1565c0; }
        .badge-in_progress { background: #fff3e0; color: #ef6c00; }
        .badge-answered { background: #e8f5e9; color: #2e7d32; }
        .badge-closed { background: #f5f5f5; color: #616161; }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: #4a90d9;
            color: #fff;
        }

        .btn-primary:hover {
            background: #3a7fc8;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .pagination {
            display: flex;
            gap: 4px;
            list-style: none;
            justify-content: center;
            margin-top: 24px;
        }

        .pagination a, .pagination span {
            display: block;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        .pagination a:hover {
            background: #f0f0f0;
        }

        .pagination .active span {
            background: #4a90d9;
            color: #fff;
        }

        .pagination .disabled span {
            color: #ccc;
        }

        .filters {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-group label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .filter-group input,
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            min-width: 150px;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #4a90d9;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('admin.tickets.index') }}" class="navbar-brand">Mini CRM</a>
        <div class="navbar-nav">
            <a href="{{ route('admin.tickets.index') }}" class="{{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">Tickets</a>
        </div>
        <div class="navbar-user">
            <span>{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
