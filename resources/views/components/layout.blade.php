<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'POS Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('scripts') <!-- Bagian ini akan menambahkan script ke dalam halaman -->
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <x-header />

        <!-- Main -->
        <main class="p-6 flex-1">
        {{ $slot }} 
        </main>
    </div>
</div>

</body>
</html>
