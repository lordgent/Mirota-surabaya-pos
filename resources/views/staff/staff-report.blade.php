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

    <!-- Konten Berdasarkan Tab -->
    @php
        $tab = request('tab') ?? 'hariini';
    @endphp

    @if($tab === 'hariini')
        <h2 class="text-lg font-semibold mb-2">Transaksi Hari Ini</h2>
        <p class="text-sm text-gray-500 mb-4">Berikut adalah transaksi yang terjadi pada tanggal {{ date('Y-m-d') }}.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">ID Transaksi</th>
                        <th class="px-4 py-2 border">Total</th>
                        <th class="px-4 py-2 border">Waktu</th>
                        <th class="px-4 py-2 border">status</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 5; $i++)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $i }}</td>
                        <td class="px-4 py-2 border">TX-HARIINI-00{{ $i }}</td>
                        <td class="px-4 py-2 border">Rp{{ number_format(10000 * $i) }}</td>
                        <td class="px-4 py-2 border">{{ now()->format('H:i:s') }}</td>
                        <td class="px-4 py-2 border">Kasir {{ $i }}</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

    @elseif($tab === 'semua')
        <h2 class="text-lg font-semibold mb-2">Semua Transaksi</h2>
        <p class="text-sm text-gray-500 mb-4">Filter transaksi berdasarkan tanggal tertentu.</p>

        <!-- Filter -->
        <form class="flex gap-2 mb-4">
        <input type="date" name="start" id="start_date" class="border rounded px-3 py-2">
        <input type="date" name="end" id="end_date" class="border rounded px-3 py-2">
        <button type="button" id="filter-btn" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </form>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">ID Transaksi</th>
                        <th class="px-4 py-2 border">Total Item</th>
                        <th class="px-4 py-2 border">Total Harga</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>
                <tbody id="transaction-list">
                </tbody>
            </table>
        </div>

        @elseif($tab === 'apriori')
    <h2 class="text-lg font-semibold mb-2">Algoritma Apriori</h2>
    <p class="text-sm text-gray-500 mb-4">Laporan asosiasi produk berdasarkan pola pembelian konsumen.</p>

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
            <tbody id="apriori-body">
                <!-- Data dari API akan masuk di sini -->
            </tbody>
        </table>
    </div>

    <div id="apriori-empty" class="text-sm text-gray-500 hidden">Belum ada data asosiasi produk yang ditemukan.</div>
@endif
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const currentUrl = new URL(window.location.href);
       
        if (currentUrl.searchParams.get("tab") === "apriori") {
            fetchAprioriData();
        }

        const filterButton = document.getElementById('filter-btn');
        filterButton.addEventListener('click', function () {
            fetchTransactions();
        });

    });
function fetchAprioriData() {
    const token = localStorage.getItem("auth_token");

fetch("/api/recommendation", {
    method: "GET",
    headers: {
        "Authorization": `Bearer ${token}`,
        "Accept": "application/json"
    }
})
.then(response => {
    if (!response.ok) throw new Error("Gagal memuat data");
    return response.json();
})
.then(data => {
    const body = document.getElementById("apriori-body");
    const loading = document.getElementById("apriori-loading");
    const table = document.getElementById("apriori-table");
    const empty = document.getElementById("apriori-empty");

    loading.classList.add("hidden");

    if (!data.data || data.length === 0) {
        empty.classList.remove("hidden");
        return;
    }

    data.data.forEach((item, index) => {
        const row = document.createElement("tr");
        row.classList.add("hover:bg-gray-50");

        const itemsHTML = item.items.map(obj =>
    `<span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded">${obj.name}</span>`
).join(" ");
        row.innerHTML = `
            <td class="px-4 py-2 border">${index + 1}</td>
            <td class="px-4 py-2 border">${itemsHTML}</td>
            <td class="px-4 py-2 border">${item.support}</td>
        `;
        body.appendChild(row);
    });

    table.classList.remove("hidden");
})
.catch(error => {
    document.getElementById("apriori-loading").textContent = "Gagal memuat data.";
    console.error(error);
});

    }

function fetchTransactions() {
    const token = localStorage.getItem('auth_token');
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const transactionList = document.getElementById('transaction-list'); 

    if (!token) {
        console.error("Token tidak ditemukan di localStorage");
        return;
    }

    if (!transactionList) {
        console.error("Element 'transaction-list' tidak ditemukan.");
        return;
    }

    const queryParams = new URLSearchParams({
        start_date: startDate,
        end_date: endDate
    });

    // Fetch data from API with the query parameters
    fetch(`/api/transactions?${queryParams.toString()}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        if (!data.data || data.data.length === 0) {
            transactionList.innerHTML = '<tr><td colspan="6" class="text-center py-4">Tidak ada transaksi ditemukan.</td></tr>';
            return;
        }

        // Clear any previous rows
        transactionList.innerHTML = '';

        // Loop through the response data and create table rows
        data.data.forEach((transaction, index) => {
            const row = document.createElement('tr');
            row.classList.add('hover:bg-gray-50');
            
            // Render each field dynamically
            row.innerHTML = `
                <td class="px-4 py-2 border">${index + 1}</td>
                <td class="px-4 py-2 border">${transaction.created_at}</td>
                <td class="px-4 py-2 border">${transaction.transaction_code}</td>
                <td class="px-4 py-2 border">${transaction.customer_name}</td>
                <td class="px-4 py-2 border">Rp${parseFloat(transaction.amount).toLocaleString()}</td>
                <td class="px-4 py-2 border">${transaction.status}</td>
            `;

            // Append the row to the table body
            transactionList.appendChild(row);
        });
    })
    .catch(error => {
        console.error('Error fetching transactions:', error);
        transactionList.innerHTML = '<tr><td colspan="6" class="text-center py-4">Gagal memuat transaksi.</td></tr>';
    });
}




</script>
@endpush

</x-layout>