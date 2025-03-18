<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('scripts') 
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <x-admin-sidebar />

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

<script src="/js/helpers.js"></script>
</body>
</html>
