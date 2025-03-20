<x-layout>
    @section('title', 'Cashier')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Produk -->
        <div class="lg:col-span-2 bg-white rounded shadow p-4">
            <div class="mb-4">
                <input type="text" id="search" placeholder="Cari produk..."
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring">
            </div>

            <div id="product-list" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <!-- Produk akan dimuat di sini -->
            </div>
            <div class="mt-4 flex justify-center" id="pagination-controls"></div>

        </div>

        <!-- Transaksi -->
        <div class="bg-white rounded shadow p-4 flex flex-col">
            <h2 class="text-lg font-semibold mb-4">Keranjang</h2>


            <!-- Keranjang Produk -->
            <div class="flex-1 overflow-y-auto mb-4" id="cart">
                <!-- Keranjang Produk -->
            </div>

            <!-- Form untuk Customer Name dan Payment Method -->
            <div class="mb-4">
                <label for="customer-name" class="block text-sm font-medium text-gray-700">Nama Customer</label>
                <input type="text" id="customer-name" placeholder="Masukkan nama customer"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring">
            </div>

            <div class="mb-4">
                <label for="payment-method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select id="payment-method" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring">
                    <option value="cash">Tunai</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>

            <div class="border-t pt-4 space-y-2">
                <div class="flex justify-between font-medium">
                    <span>Total:</span>
                    <span class="text-blue-600 font-bold" id="total">Rp0</span>
                </div>

                <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700" onclick="handlePayment()">
                    Bayar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const token = localStorage.getItem('auth_token');

            // Memuat produk dari API
            async function loadProducts(page = 1, search = '') {
    try {
        const response = await fetch(`/api/products?page=${page}&per_page=10&search=${search}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (data.status === 'success') {
            const products = data.data.data; // karena pakai paginate
            const productList = document.getElementById('product-list');
            productList.innerHTML = ''; // Kosongkan produk sebelumnya

            products.forEach(product => {
                const productButton = document.createElement('button');
                productButton.classList.add('bg-gray-100', 'p-4', 'rounded', 'shadow', 'hover:bg-blue-100', 'product-item');
                productButton.dataset.productId = product.id;
                productButton.innerHTML = `
                    <img src="{{ asset('storage/') }}/${product.photo}" alt="${product.name}" class="w-full h-24 object-cover rounded mb-2" onerror="this.onerror=null;this.src='/images/image-error.jpg';"/>
                    <div class="font-semibold text-sm text-gray-700">${product.name}</div>
                    <div class="text-blue-600 font-bold text-lg mt-1">Rp${parseFloat(product.price).toLocaleString()}</div>
                `;
                productButton.onclick = function () {
                    addToCart(product);
                };
                productList.appendChild(productButton);
            });

            // Buat tombol pagination
            renderPagination(data.data);
        } else {
            console.error('Gagal mengambil produk:', data.message);
        }
    } catch (error) {
        console.error('Terjadi kesalahan saat mengambil data produk:', error);
    }
}

function renderPagination(pagination) {
    const container = document.getElementById('pagination-controls');
    container.innerHTML = '';

    const { current_page, last_page } = pagination;

    for (let i = 1; i <= last_page; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = `mx-1 px-3 py-1 rounded ${i === current_page ? 'bg-blue-600 text-white' : 'bg-gray-200'}`;
        btn.onclick = () => loadProducts(i);
        container.appendChild(btn);
    }
}

            // Keranjang Produk
            let cart = [];
            let total = 0;

            function addToCart(product) {
                const cartItem = cart.find(item => item.id === product.id);
                if (cartItem) {
                    cartItem.quantity++;
                } else {
                    cart.push({ ...product, quantity: 1 });
                }

                updateCart();
            }

            function updateCart() {
                const cartList = document.getElementById('cart');
                const totalPrice = document.getElementById('total');
                cartList.innerHTML = '';
                total = 0;

                cart.forEach(item => {
                    const cartRow = document.createElement('div');
                    cartRow.classList.add('flex', 'justify-between', 'items-center', 'mb-3');
                    cartRow.innerHTML = `
                            <div class="flex items-center gap-6">
                                <div class="font-medium">
                                <button class="px-2 py-1 text-sm bg-red-100 text-red-600 rounded" onclick="removeFromCart(${item.id})">Hapus</button>
                                <p>${item.name}</p>
                                <p>Rp ${parseFloat(item.price).toLocaleString()}</p>
                                </div>
                            </div>
                            <div>
                                 <div class="text-sm text-gray-500">
                                    <button class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded" onclick="updateQuantity(${item.id}, -1)">-</button>
                                    <span class="mx-2">${item.quantity}</span>
                                    <button class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded" onclick="updateQuantity(${item.id}, 1)">+</button>
                                </div>
                            </div>
                        `;
                    cartList.appendChild(cartRow);
                    total += item.price * item.quantity;
                });

                totalPrice.textContent = `Rp${total.toLocaleString()}`;
            }

            function updateQuantity(productId, change) {
                const cartItem = cart.find(item => item.id === productId);
                if (cartItem) {
                    cartItem.quantity += change;
                    if (cartItem.quantity <= 0) {
                        removeFromCart(productId);
                    } else {
                        updateCart();
                    }
                }
            }

            function removeFromCart(productId) {
                const index = cart.findIndex(item => item.id === productId);
                if (index > -1) {
                    cart.splice(index, 1);
                    updateCart();
                }
            }

            function handlePayment() {
                const customerName = document.getElementById('customer-name').value;
                const paymentMethod = document.getElementById('payment-method').value;

                if (!customerName) {
                    alert('Nama customer harus diisi');
                    return;
                }

                if (cart.length === 0) {
                    alert('Keranjang masih kosong');
                    return;
                }

                const paymentData = {
                    customer_name: customerName,
                    payment_method: paymentMethod,
                    orders: cart.map(item => ({
                        product_id: item.id,
                        qty: item.quantity
                    }))
                };

                fetch('/api/transaction', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(paymentData)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Pembayaran berhasil!');
                            cart = [];
                            updateCart();
                            document.getElementById('customer-name').value = '';
                            document.getElementById('payment-method').value = 'cash';
                            loadProducts(); // Optional: reload stok
                        } else {
                            alert('Gagal melakukan transaksi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Terjadi kesalahan:', error);
                        alert('Terjadi kesalahan saat mengirim data');
                    });
            }


            document.addEventListener('DOMContentLoaded', function () {
                loadProducts();
            });
        </script>
    @endpush
</x-layout>