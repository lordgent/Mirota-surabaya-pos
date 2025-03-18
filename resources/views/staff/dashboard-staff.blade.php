<x-layout>
@section('title', 'Dashboard')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Dashboard Info Cards -->
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-gray-600">Total Produk</h2>
        <p class="text-2xl font-bold text-blue-600" id="total-products">...</p>
    </div>
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-gray-600">Total Transaksi</h2>
        <p class="text-2xl font-bold text-green-600" id="total-transactions">...</p>
    </div>
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-gray-600">Pendapatan Hari Ini</h2>
        <p class="text-2xl font-bold text-yellow-600" id="today-income">...</p>
    </div>
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-gray-600">Stok Habis</h2>
        <p class="text-2xl font-bold text-red-600" id="out-of-stock">...</p>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Transaksi Terakhir</h2>
    <table class="w-full table-auto text-left">
        <thead>
            <tr>
                <th class="px-4 py-2 border-b">Tanggal</th>
                <th class="px-4 py-2 border-b">Pelanggan</th>
                <th class="px-4 py-2 border-b">Invoice</th>
                <th class="px-4 py-2 border-b">Total</th>
                <th class="px-4 py-2 border-b">Status</th>
            </tr>
        </thead>
        <tbody id="transaction-list">
            <!-- Transaksi akan ditambahkan melalui JavaScript -->
        </tbody>
    </table>
    <div id="pagination" class="mt-4 flex justify-center items-center gap-2">

    </div>
</div>

@push('scripts')
<script>
    let currentPage = 1;
    const perPage = 5;
            
    const today = formatDate(new Date());

    async function fetchTransactions(page = 1) {
        const token = localStorage.getItem('auth_token'); 
        if (!token) return console.error("Token tidak ditemukan di localStorage");


        try {
            const response = await fetch(`/api/transactions?start_date=${today}&end_date=${today}&page=${page}&per_page=${perPage}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`, 
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) throw new Error('Gagal memuat transaksi');

            const result = await response.json();
            displayTransactions(result.data);
            renderPagination(result.meta);
        } catch (error) {
            console.error(error);
        }
    }

    function displayTransactions(transactions) {
        const transactionList = document.getElementById('transaction-list');
        transactionList.innerHTML = '';

        transactions.forEach(transaction => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2">${formatDateNew(transaction.created_at)}</td>
                <td class="px-4 py-2">${transaction.customer_name}</td>
                <td class="px-4 py-2">${transaction.transaction_code}</td>
                <td class="px-4 py-2">Rp${parseFloat(transaction.amount).toLocaleString()}</td>
                <td class="px-4 py-2 ${transaction.status === 'completed' ? 'text-green-500' : 'text-yellow-500'}">${transaction.status}</td>
            `;
            transactionList.appendChild(row);
        });
    }

    function renderPagination(meta) {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        for (let i = 1; i <= meta.last_page; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 border rounded ${i === meta.current_page ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'}`;
            btn.onclick = () => {
                currentPage = i;
                fetchTransactions(i);
            };
            paginationContainer.appendChild(btn);
        }
    }

    fetchTransactions(currentPage);


</script>
@endpush

</x-layout>
