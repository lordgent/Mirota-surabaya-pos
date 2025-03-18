<x-layout>
    @section('title', 'Management Product')

    <div class="bg-white rounded shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold">Daftar Produk</h1>
        </div>

        <!-- Search Form -->
        <div class="mb-4 flex gap-2">
            <input type="text" id="search-input" placeholder="Cari produk..." class="border px-3 py-2 rounded w-1/3">
            <button onclick="loadProducts(1)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Cari</button>
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
                        <th class="px-4 py-2 border">Foto</th>
                    </tr>
                </thead>
                <tbody id="product-list">
                    <!-- Data produk dimuat di sini -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-4 flex justify-end gap-2"></div>
    </div>

    @push('scripts')
    <script>
        async function loadProducts(page = 1) {
            const token = localStorage.getItem('auth_token');
            const searchQuery = document.getElementById('search-input').value;

            try {
                const response = await fetch(`/api/products?page=${page}&search=${encodeURIComponent(searchQuery)}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    const productList = document.getElementById('product-list');
                    const pagination = document.getElementById('pagination');
                    productList.innerHTML = '';
                    pagination.innerHTML = '';

                    const products = data.data.data;
                    const currentPage = data.data.current_page;
                    const lastPage = data.data.last_page;

                    products.forEach((product, index) => {
                        const row = document.createElement('tr');
                        row.classList.add('hover:bg-gray-50');
                        row.innerHTML = `
                            <td class="px-4 py-2 border">${(currentPage - 1) * data.data.per_page + index + 1}</td>
                            <td class="px-4 py-2 border">${product.name}</td>
                            <td class="px-4 py-2 border">${product.category_id}</td>
                            <td class="px-4 py-2 border">Rp${parseFloat(product.price).toLocaleString()}</td>
                            <td class="px-4 py-2 border">${product.stock}</td>
                            <td class="px-4 py-2 border">
                                <img src="http://localhost:8000/storage/${product.photo}" alt="${product.name}" class="w-16 h-16 object-cover rounded-lg">
                            </td>
                        `;
                        productList.appendChild(row);
                    });

                    // Render pagination
                    for (let i = 1; i <= lastPage; i++) {
                        const button = document.createElement('button');
                        button.className = `px-3 py-1 border rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-black hover:bg-gray-100'}`;
                        button.innerText = i;
                        button.addEventListener('click', () => loadProducts(i));
                        pagination.appendChild(button);
                    }
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
