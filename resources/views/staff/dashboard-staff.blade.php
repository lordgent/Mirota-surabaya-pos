<x-layout>
@section('title', 'Dashboard')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Dashboard Info Cards -->
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
</div>

@push('scripts')
<script>


    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    async function fetchTransactions() {
        const token = localStorage.getItem('auth_token'); 

        if (!token) {
            console.error("Token tidak ditemukan di localStorage");
            return;
        }

        const now = new Date();
        const startDate = formatDate(now);  
        const endDate = formatDate(now); 

        try {
            const response = await fetch(`/api/transactions?start_date=${startDate}&end_date=${endDate}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`, 
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Gagal memuat transaksi');
            }

            const data = await response.json();
            displayTransactions(data.data);
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

    // Memanggil fungsi fetchTransactions dengan tanggal sekarang
    fetchTransactions();  // Mengambil transaksi hari ini berdasarkan tanggal sekarang
</script>
@endpush
</x-layout>
