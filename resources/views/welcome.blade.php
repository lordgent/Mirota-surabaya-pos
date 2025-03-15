<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIROTA - Portal Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <section class="relative bg-cover bg-center h-screen bg-blue-600">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        
        <div class="relative z-10 flex items-center justify-center text-center h-full px-6">
            <div>
                <h1 class="text-5xl font-extrabold text-white leading-tight mb-4">Selamat Datang di Portal Karyawan MIROTA</h1>
                <p class="text-xl text-white mb-8">Sistem Manajemen Karyawan untuk Kasir, Stok Produk, dan Laporan yang lebih efisien</p>
                
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-full text-lg hover:bg-indigo-700 transition duration-300">Masuk ke Akun</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white text-gray-800">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-semibold text-indigo-700">Fitur Utama Portal Karyawan</h2>
            <p class="text-xl text-gray-500 mt-4 mb-12">Meningkatkan efisiensi operasional dengan fitur-fitur terbaik untuk kasir, manajemen stok, dan laporan.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

            <div class="bg-indigo-100 p-8 rounded-lg shadow-lg">
                    <div class="mb-6 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 9h14"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v18"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Kasir & Transaksi</h3>
                    <p class="text-gray-600 mt-4">Kelola transaksi dengan mudah. Sistem kasir yang cepat dan tepat untuk setiap transaksi harian.</p>
                </div>

                <div class="bg-indigo-100 p-8 rounded-lg shadow-lg">
                    <div class="mb-6 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7l4 4 4-4"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 17l8 4 8-4"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 13l8 4 8-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Manajemen Stok Produk</h3>
                    <p class="text-gray-600 mt-4">Pantau persediaan produk dan kelola stok barang dengan mudah.</p>
                </div>

                <div class="bg-indigo-100 p-8 rounded-lg shadow-lg">
                    <div class="mb-6 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16M4 12h16"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Laporan Keuangan & Penjualan</h3>
                    <p class="text-gray-600 mt-4">Akses laporan penjualan dan laporan keuangan untuk analisis dan keputusan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gray-50 text-center">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-semibold text-indigo-700">Tentang Portal MIROTA</h2>
            <p class="text-xl text-gray-600 mt-4 mb-12">Portal MIROTA dirancang untuk mempermudah karyawan dalam melakukan tugas-tugas kasir, mengelola stok produk, serta menghasilkan laporan secara cepat dan akurat.</p>
        </div>
    </section>

    <footer class="bg-indigo-700 text-white py-6 text-center">
        <p>&copy; 2025 MIROTA. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>
