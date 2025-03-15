<x-layout>
@section('title', 'Dashboard')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-gray-600">Total Produk</h2>
            <p class="text-2xl font-bold text-blue-600">150</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-gray-600">Total Transaksi</h2>
            <p class="text-2xl font-bold text-green-600">420</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-gray-600">Pendapatan Hari Ini</h2>
            <p class="text-2xl font-bold text-yellow-600">Rp1.250.000</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-gray-600">Stok Habis</h2>
            <p class="text-2xl font-bold text-red-600">8</p>
        </div>
    </div>

    <div class="mt-8 bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Transaksi Terakhir</h2>
        <table class="w-full table-auto text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b">Tanggal</th>
                    <th class="px-4 py-2 border-b">Invoice</th>
                    <th class="px-4 py-2 border-b">Total</th>
                    <th class="px-4 py-2 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-2">2025-03-15</td>
                    <td class="px-4 py-2">#INV-001</td>
                    <td class="px-4 py-2">Rp300.000</td>
                    <td class="px-4 py-2 text-green-500">Selesai</td>
                </tr>
                <tr>
                    <td class="px-4 py-2">2025-03-14</td>
                    <td class="px-4 py-2">#INV-002</td>
                    <td class="px-4 py-2">Rp250.000</td>
                    <td class="px-4 py-2 text-yellow-500">Pending</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-layout>
