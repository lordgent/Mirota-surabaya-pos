<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="p-4 text-xl font-bold text-center border-b">MIROTA INTERNAL</div>
        <nav class="mt-4">
            <a href="/staff/dashboard" class="block px-6 py-3 hover:bg-gray-100">📊 Dashboard</a>
            <a href="/staff/products" class="block px-6 py-3 hover:bg-gray-100">📦 Produk</a>
            <a href="/staff/cashier" class="block px-6 py-3 hover:bg-gray-100">💸 Transaksi</a>
            <a href="/staff/reports" class="block px-6 py-3 hover:bg-gray-100">📈 Laporan</a>
            <a href="/logout" class="block px-6 py-3 text-red-500 hover:bg-red-50">🚪 Logout</a>
        </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">@yield('title', 'Dashboard')</h1>
            <div class="text-sm text-gray-600">👤 Admin</div>
        </header>

        <!-- Main -->
        <main class="p-6 flex-1">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
