<x-layout>
    @section('title', 'Management Product')

    <div class="bg-white rounded shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold">Daftar Produk</h1>
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
                        <th class="px-4 py-2 border">Foto</th> <!-- Kolom Foto -->
                    </tr>
                </thead>
                <tbody id="product-list">
                    <!-- Data produk akan dimuat di sini -->
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        console.log('runn'); // Pastikan log ini muncul di console

        async function loadProducts() {
            const token = localStorage.getItem('auth_token');
            console.log('Token:', token); // Periksa apakah token ada di localStorage

            try {
                const response = await fetch('/api/products', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    const products = data.data;
                    const productList = document.getElementById('product-list');
                    productList.innerHTML = '';

                    products.forEach((product, index) => {
                        const row = document.createElement('tr');
                        row.classList.add('hover:bg-gray-50');
                        row.innerHTML = `
                            <td class="px-4 py-2 border">${index + 1}</td>
                            <td class="px-4 py-2 border">${product.name}</td>
                            <td class="px-4 py-2 border">${product.category_id}</td>
                            <td class="px-4 py-2 border">Rp${parseFloat(product.price).toLocaleString()}</td>
                            <td class="px-4 py-2 border">${product.stock}</td>
                            <td class="px-4 py-2 border">
                                <img src="http://localhost:8000/storage/${product.photo}" alt="${product.name}" class="w-16 h-16 object-cover rounded-lg">
                            </td> <!-- Menampilkan gambar produk -->
                        `;
                        productList.appendChild(row);
                    });
                } else {
                    console.error('Gagal mengambil produk:', data.message);
                }
            } catch (error) {
                console.error('Terjadi kesalahan saat mengambil data produk:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadProducts();
        });
    </script>
    @endpush
</x-layout>
