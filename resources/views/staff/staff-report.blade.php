<x-layout>
@section('title', 'Laporan Transaksi')

<div class="bg-white rounded shadow p-6">
    <h1 class="text-xl font-semibold mb-4">Laporan</h1>

    <!-- Tab Menu -->
    <div class="flex border-b mb-6 space-x-6">
        <a href="?tab=hariini" class="@if(request('tab') === 'hariini' || !request('tab')) border-b-2 border-blue-600 text-blue-600 @else text-gray-500 @endif pb-2 font-medium">
            Transaksi Hari Ini
        </a>
        <a href="?tab=semua" class="@if(request('tab') === 'semua') border-b-2 border-blue-600 text-blue-600 @else text-gray-500 @endif pb-2 font-medium">
            Semua Transaksi
        </a>
        <a href="?tab=apriori" class="@if(request('tab') === 'apriori') border-b-2 border-blue-600 text-blue-600 @else text-gray-500 @endif pb-2 font-medium">
            Algoritma Apriori
        </a>
    </div>

    @php
        $tab = request('tab') ?? 'hariini';
    @endphp

    <!-- === Tab Hari Ini === -->
    @if($tab === 'hariini')
        <h2 class="text-lg font-semibold mb-2">Transaksi Hari Ini</h2>
        <p class="text-sm text-gray-500 mb-4">Berikut adalah transaksi pada tanggal {{ date('Y-m-d') }}.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Nama Pelanggan</th>
                        <th class="px-4 py-2 border">Kode</th>
                        <th class="px-4 py-2 border">Total</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody id="transaction-list-now"></tbody>
            </table>
            <div class="mt-4 flex justify-between items-center" id="pagination-controls-now">
                <button id="prev-page-now" class="bg-gray-200 text-gray-700 px-4 py-2 rounded disabled:opacity-50">Sebelumnya</button>
                <span id="current-page-now" class="text-sm text-gray-700">Halaman 1</span>
                <button id="next-page-now" class="bg-gray-200 text-gray-700 px-4 py-2 rounded disabled:opacity-50">Selanjutnya</button>
            </div>
        </div>

    <!-- === Tab Semua === -->
    @elseif($tab === 'semua')
        <h2 class="text-lg font-semibold mb-2">Semua Transaksi</h2>
        <p class="text-sm text-gray-500 mb-4">Filter transaksi berdasarkan tanggal.</p>

        <form class="flex gap-2 mb-4">
            <input type="date" id="start_date" class="border rounded px-3 py-2">
            <input type="date" id="end_date" class="border rounded px-3 py-2">
            <button type="button" id="filter-btn" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Nama Pelanggan</th>
                        <th class="px-4 py-2 border">Kode</th>
                        <th class="px-4 py-2 border">Total</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>
                <tbody id="transaction-list"></tbody>
            </table>
            <div class="mt-4 flex justify-between items-center" id="pagination-controls">
                <button id="prev-page" class="bg-gray-200 text-gray-700 px-4 py-2 rounded disabled:opacity-50">Sebelumnya</button>
                <span id="current-page" class="text-sm text-gray-700">Halaman 1</span>
                <button id="next-page" class="bg-gray-200 text-gray-700 px-4 py-2 rounded disabled:opacity-50">Selanjutnya</button>
            </div>
        </div>

    <!-- === Tab Apriori === -->
    @elseif($tab === 'apriori')
        <h2 class="text-lg font-semibold mb-2">Algoritma Apriori</h2>
        <p class="text-sm text-gray-500 mb-4">Laporan asosiasi produk berdasarkan pola pembelian.</p>
        <div id="apriori-loading" class="text-sm text-gray-500">Memuat data...</div>
        <div id="apriori-table" class="overflow-x-auto hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Produk Terkait</th>
                        <th class="px-4 py-2 border">Support</th>
                    </tr>
                </thead>
                <tbody id="apriori-body"></tbody>
            </table>
        </div>
        <div id="apriori-empty" class="text-sm text-gray-500 hidden">Belum ada data asosiasi.</div>
    @endif
</div>

@push('scripts')
<script>
let currentPage = 1;
let lastPage = 1;



document.addEventListener("DOMContentLoaded", () => {
    const tab = new URL(window.location.href).searchParams.get("tab") || "hariini";

    if (tab === "apriori") fetchAprioriData();
    if (tab === "hariini") fetchTransactionsNow();
    
    document.getElementById('filter-btn')?.addEventListener('click', () => {
        currentPage = 1;
        fetchTransactions();
    });

    document.getElementById('prev-page')?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchTransactions();
        }
    });

    document.getElementById('next-page')?.addEventListener('click', () => {
        if (currentPage < lastPage) {
            currentPage++;
            fetchTransactions();
        }
    });

    document.getElementById('prev-page-now')?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchTransactionsNow();
        }
    });

    document.getElementById('next-page-now')?.addEventListener('click', () => {
        if (currentPage < lastPage) {
            currentPage++;
            fetchTransactionsNow();
        }
    });
});

// Fetch semua transaksi
function fetchTransactions() {
    const token = localStorage.getItem('auth_token');
    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    const list = document.getElementById('transaction-list');

    if (!token) return console.error("Token tidak ditemukan");

    const query = new URLSearchParams({ start_date: start, end_date: end, page: currentPage });
    
    fetch(`/api/transactions?${query.toString()}`, {
        headers: { 'Authorization': `Bearer ${token}` }
    })
    .then(res => res.json())
    .then(({ data, meta }) => {
        list.innerHTML = '';
        lastPage = meta?.last_page || 1;

        if (data.length === 0) {
            list.innerHTML = `<tr><td colspan="5" class="text-center py-4">Tidak ada transaksi ditemukan.</td></tr>`;
            return;
        }

        data.forEach(tx => {
            list.innerHTML += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">${formatDateLocal(tx.created_at)}</td>
                    <td class="px-4 py-2">${tx.customer_name}</td>
                    <td class="px-4 py-2">${tx.transaction_code}</td>
                    <td class="px-4 py-2">Rp${parseFloat(tx.amount).toLocaleString()}</td>
                    <td class="px-4 py-2 ${tx.status === 'completed' ? 'text-green-500' : 'text-yellow-500'}">${tx.status}</td>
                </tr>`;
        });

        document.getElementById('current-page').textContent = `Halaman ${currentPage}`;
        document.getElementById('prev-page').disabled = currentPage === 1;
        document.getElementById('next-page').disabled = currentPage === lastPage;
    });
}

// Fetch transaksi hari ini
function fetchTransactionsNow() {
    const token = localStorage.getItem('auth_token');
    const today = formatDate(new Date());
    const list = document.getElementById('transaction-list-now');

    if (!token) return console.error("Token tidak ditemukan");

    fetch(`/api/transactions?start_date=${today}&end_date=${today}&page=${currentPage}`, {
        headers: { 'Authorization': `Bearer ${token}` }
    })
    .then(res => res.json())
    .then(({ data, meta }) => {
        list.innerHTML = '';
        lastPage = meta?.last_page || 1;

        if (data.length === 0) {
            list.innerHTML = `<tr><td colspan="5" class="text-center py-4">Tidak ada transaksi hari ini.</td></tr>`;
            return;
        }

        data.forEach(tx => {
            list.innerHTML += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">${formatDateLocal(tx.created_at)}</td>
                    <td class="px-4 py-2">${tx.customer_name}</td>
                    <td class="px-4 py-2">${tx.transaction_code}</td>
                    <td class="px-4 py-2">Rp${parseFloat(tx.amount).toLocaleString()}</td>
                    <td class="px-4 py-2 ${tx.status === 'completed' ? 'text-green-500' : 'text-yellow-500'}">${tx.status}</td>
                    <td>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm transition duration-200 ease-in-out">
                        Perbarui
                    </button>
                    </td>
                </tr>`;
        });

        document.getElementById('current-page-now').textContent = `Halaman ${currentPage}`;
        document.getElementById('prev-page-now').disabled = currentPage === 1;
        document.getElementById('next-page-now').disabled = currentPage === lastPage;
    });
}

// Fetch apriori
function fetchAprioriData() {
    const token = localStorage.getItem('auth_token');

    fetch(`/api/apriori`, {
        headers: { 'Authorization': `Bearer ${token}` }
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('apriori-body');
        const loading = document.getElementById('apriori-loading');
        const table = document.getElementById('apriori-table');
        const empty = document.getElementById('apriori-empty');

        loading.classList.add('hidden');

        if (data.length === 0) {
            empty.classList.remove('hidden');
            return;
        }

        table.classList.remove('hidden');
        data.forEach((item, index) => {
            tbody.innerHTML += `
                <tr>
                    <td class="px-4 py-2 border">${index + 1}</td>
                    <td class="px-4 py-2 border">${item.products.join(', ')}</td>
                    <td class="px-4 py-2 border">${item.support}</td>
                </tr>`;
        });
    });
}
</script>
@endpush
</x-layout>
