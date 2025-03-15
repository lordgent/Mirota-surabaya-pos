<x-layout>
@section('title', 'Cashier')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Produk -->
    <div class="lg:col-span-2 bg-white rounded shadow p-4">
        <div class="mb-4">
            <input
                type="text"
                placeholder="Cari produk..."
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring"
            >
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            {{-- Loop produk --}}
            @for ($i = 1; $i <= 10; $i++)
                <button class="bg-gray-100 p-4 rounded shadow hover:bg-blue-100">
                    <div class="font-semibold text-sm text-gray-700">Produk {{ $i }}</div>
                    <div class="text-blue-600 font-bold text-lg mt-1">Rp{{ number_format($i * 5000) }}</div>
                </button>
            @endfor
        </div>
    </div>

    <!-- Transaksi -->
    <div class="bg-white rounded shadow p-4 flex flex-col">
        <h2 class="text-lg font-semibold mb-4">Keranjang</h2>

        <div class="flex-1 overflow-y-auto mb-4">
            {{-- Contoh produk yang ditambahkan --}}
            @for ($i = 1; $i <= 3; $i++)
            <div class="flex justify-between items-center mb-3">
                <div>
                    <div class="font-medium">Produk {{ $i }}</div>
                    <div class="text-sm text-gray-500">Rp{{ number_format(5000 * $i) }} x 1</div>
                </div>
                <div>
                    <button class="px-2 py-1 text-sm bg-red-100 text-red-600 rounded">Hapus</button>
                </div>
            </div>
            @endfor
        </div>

        <div class="border-t pt-4 space-y-2">
            <div class="flex justify-between font-medium">
                <span>Total:</span>
                <span class="text-blue-600 font-bold">Rp22.000</span>
            </div>

            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Bayar
            </button>
        </div>
    </div>
</div>
</x-layout>