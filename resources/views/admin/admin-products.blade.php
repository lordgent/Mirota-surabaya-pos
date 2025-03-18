<x-admin-layout>
    @section('title', 'Manajemen Produk')

    <div class="bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <a href="/admin/product/add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Produk
            </a>
        </div>

        <!-- Search -->
        <div class="mb-4 flex gap-2">
            <input type="text" id="search-input" placeholder="Cari produk..." class="border px-3 py-2 rounded w-1/3">
            <button onclick="loadProducts(1)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cari</button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Foto</th>
                        <th class="px-4 py-2 border">Kode</th>
                        <th class="px-4 py-2 border">Nama Produk</th>
                        <th class="px-4 py-2 border">Kategori</th>
                        <th class="px-4 py-2 border">Harga</th>
                        <th class="px-4 py-2 border">Stok</th>
                        <th class="px-4 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody id="product-list">
                    <!-- Data produk akan dimuat lewat JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-4 flex justify-end gap-2"></div>
        <div id="loading-indicator" class="hidden fixed inset-0 flex items-center justify-center bg-white/50 z-50">
            <x-loading-data>Loading data...</x-loading-data>
        </div>

    </div>

    @push('scripts')
    <script>
      async function loadProducts(page = 1) {
    const loading = document.getElementById('loading-indicator');
    const token = localStorage.getItem('auth_token');
    const searchQuery = document.getElementById('search-input').value;

    if (loading) loading.classList.remove('hidden');

    try {
        const response = await fetch(`/api/admin/products?page=${page}&search=${encodeURIComponent(searchQuery)}`, {
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
                    <td class="px-4 py-2 border">
                        <img 
                            src="http://localhost:8000/storage/${product.photo}" 
                            alt="${product.name}" 
                            class="w-16 h-16 object-cover rounded-lg"
                            onerror="this.onerror=null;this.src='/images/image-error.jpg';"/>                   
                     </td>
                    <td class="px-4 py-2 border">${product.product_code}</td>
                    <td class="px-4 py-2 border">${product.name}</td>
                    <td class="px-4 py-2 border">${product.category.name}</td>
                    <td class="px-4 py-2 border">Rp${parseFloat(product.price).toLocaleString()}</td>
                    <td class="px-4 py-2 border">${product.stock}</td>
                    <td class="px-4 py-2 border">
                        <div class="flex items-center gap-4">
                            <button class="bg-blue-500 text-white p-2">Edit</button>
                             <button class="bg-red-500 text-white p-2">Hapus</button>
                        </div> 
                    </td>
                `;
                productList.appendChild(row);
            });

            // Pagination
            for (let i = 1; i <= lastPage; i++) {
                const btn = document.createElement('button');
                btn.className = `px-3 py-1 border rounded ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-white text-black hover:bg-gray-100'}`;
                btn.innerText = i;
                btn.addEventListener('click', () => loadProducts(i));
                pagination.appendChild(btn);
            }

        } else {
            console.error('Gagal mengambil produk:', data.message);
        }

    } catch (error) {
        console.error('Error:', error);
    } finally {
        if (loading) loading.classList.add('hidden'); // sembunyikan loading
    }
}


        document.addEventListener('DOMContentLoaded', () => {
            loadProducts();
        });
    </script>
    @endpush
</x-admin-layout>
