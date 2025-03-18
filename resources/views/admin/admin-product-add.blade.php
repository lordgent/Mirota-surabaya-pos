<x-admin-layout>
    @section('title', 'Tambah Produk')

    <div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
        <h1 class="text-xl font-semibold mb-6">Tambah Produk</h1>

        <form id="add-product-form" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Nama Produk</label>
                <input type="text" id="name" name="name" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="product_code" class="block font-medium mb-1">Kode Produk</label>
                <input type="text" id="product_code" name="product_code" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="category_id" class="block font-medium mb-1">Kategori</label>
                <select id="category_id" name="category_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Pilih Kategori --</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="price" class="block font-medium mb-1">Harga</label>
                <input type="number" id="price" name="price" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="stock" class="block font-medium mb-1">Stok</label>
                <input type="number" id="stock" name="stock" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium mb-1">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="w-full border px-3 py-2 rounded"></textarea>
            </div>

            <div class="mb-4">
                <label for="photo" class="block font-medium mb-1">Foto Produk</label>
                <input type="file" id="photo" name="photo" accept="image/*" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
        <div id="loading-indicator" class="hidden fixed inset-0 flex items-center justify-center bg-white/50 z-50">
            <x-loading-data>Loading</x-loading-data>
        </div>
    </div>

    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetchCategories();

        const form = document.getElementById('add-product-form');
        const loading = document.getElementById('loading-indicator');

        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (loading) loading.classList.remove('hidden');

                const token = localStorage.getItem('auth_token');
                const formData = new FormData(form);

                try {
                    const response = await fetch('/api/admin/product', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        alert('Produk berhasil ditambahkan!');
                        window.location.href = '/admin/products';
                    } else {
                        alert('Gagal menambahkan produk: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan produk.');
                } finally {
                if (loading) loading.classList.add('hidden');
            }
            });
        }
    });

    async function fetchCategories() {
        const token = localStorage.getItem('auth_token');
        const res = await fetch('/api/admin/categories', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        const data = await res.json();
        const select = document.getElementById('category_id');
        if (data.status === 'success') {
            data.data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.text = category.name;
                select.appendChild(option);
            });
        }
    }
</script>
@endpush

</x-admin-layout>
