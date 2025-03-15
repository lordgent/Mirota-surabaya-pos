<x-layout>
@section('title', 'Management Product')

<div class="bg-white rounded shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold">Daftar Produk</h1>
        <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Produk
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nama Produk</th>
                    <th class="px-4 py-2 border">Kategori</th>
                    <th class="px-4 py-2 border">Harga</th>
                    <th class="px-4 py-2 border">Stok</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 5; $i++)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $i }}</td>
                    <td class="px-4 py-2 border">Produk {{ $i }}</td>
                    <td class="px-4 py-2 border">Minuman</td>
                    <td class="px-4 py-2 border">Rp{{ number_format(5000 * $i) }}</td>
                    <td class="px-4 py-2 border">10</td>
                    <td class="px-4 py-2 border text-center">
                        <a href="#" class="text-blue-600 hover:underline text-sm">Edit</a> |
                        <a href="#" class="text-red-600 hover:underline text-sm">Hapus</a>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
</x-layout>