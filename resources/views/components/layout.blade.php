<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'POS Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

<script src="{{ asset('js/helpers.js') }}"></script>
@stack('scripts') 
</body>
</html>
