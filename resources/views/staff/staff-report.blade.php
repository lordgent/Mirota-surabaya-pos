@extends('layouts.layout')

@section('title', 'Laporan')

@section('content')
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
                        <th class="px-4 py-2 border">Kasir</th>
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
            <input type="date" name="start" class="border rounded px-3 py-2">
            <input type="date" name="end" class="border rounded px-3 py-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
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
                        <th class="px-4 py-2 border">Kasir</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 5; $i++)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $i }}</td>
                        <td class="px-4 py-2 border">2025-03-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-2 border">TX-000{{ $i }}</td>
                        <td class="px-4 py-2 border">{{ rand(1, 5) }}</td>
                        <td class="px-4 py-2 border">Rp{{ number_format(5000 * $i) }}</td>
                        <td class="px-4 py-2 border">Kasir {{ $i }}</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

    @elseif($tab === 'apriori')
        <h2 class="text-lg font-semibold mb-2">Algoritma Apriori</h2>
        <p class="text-sm text-gray-500 mb-4">Laporan asosiasi produk berdasarkan pola pembelian konsumen.</p>

        <!-- Contoh hasil apriori -->
        <div class="bg-gray-100 rounded p-4">
            <p class="text-sm font-medium text-gray-700">Contoh Hasil Apriori:</p>
            <ul class="list-disc ml-6 mt-2 text-gray-600 text-sm">
                <li>Jika beli <strong>Teh Botol</strong>, maka kemungkinan beli <strong>Gorengan</strong> (75%)</li>
                <li>Jika beli <strong>Nasi Uduk</strong>, maka kemungkinan beli <strong>Air Mineral</strong> (62%)</li>
                <li>Jika beli <strong>Indomie</strong>, maka kemungkinan beli <strong>Telur</strong> (80%)</li>
            </ul>
        </div>
    @endif
</div>
@endsection
