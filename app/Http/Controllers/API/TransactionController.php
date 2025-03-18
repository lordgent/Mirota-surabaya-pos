<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{


    public function getTransactionList(Request $request)
{
    // Validasi input tanggal
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Ambil parameter start_date dan end_date
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    // Ambil jumlah item per halaman (opsional, default 10)
    $perPage = $request->input('per_page', 10);

    try {
        // Query transaksi dengan filter dan pagination
        $transactions = Transaction::whereBetween('created_at', [
                                        $startDate . ' 00:00:00',
                                        $endDate . ' 23:59:59'
                                    ])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage);

        return response()->json([
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions->items(),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve transactions',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    public function createTransactionUser(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'payment_method' => 'required|in:bank_transfer,credit_card,cash,e_wallet',
            'orders' => 'required|array|min:1',
            'orders.*.product_id' => 'required|exists:products,id',
            'orders.*.qty' => 'required|integer|min:1',
        ]);
    
        DB::beginTransaction();
    
        try {
            $totalAmount = 0;
    
            foreach ($request->orders as $orderData) {
                $product = Product::findOrFail($orderData['product_id']);
                $subtotal = $product->price * $orderData['qty'];
                $totalAmount += $subtotal;
            }
    
            $today = now()->format('Ymd');
            $latestTransaction = Transaction::whereDate('created_at', now()->toDateString())
                                    ->orderBy('id', 'desc')
                                    ->first();
    
            $nextNumber = $latestTransaction ? ((int)substr($latestTransaction->transaction_code, -4)) + 1 : 1;
            $transactionCode = 'MRTA-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'customer_name' => $request->customer_name,
                'uri_' => $request->uri_,
                'amount' => $totalAmount,
                'cashier' => Auth::id(),
                'payment_method' => $request->payment_method,
            ]);
    
            foreach ($request->orders as $orderData) {
                $product = Product::findOrFail($orderData['product_id']);
                $subtotal = $product->price * $orderData['qty'];
    
                Order::create([
                    'product_id' => $orderData['product_id'],
                    'qty' => $orderData['qty'],
                    'subtotal' => $subtotal,
                    'transaction_id' => $transaction->id,
                ]);
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaction->load('orders'),
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'message' => 'Transaction failed',
                'error' => $e->getMessage(),
            ], 500);
        }
}

public function aprioriRecommendation()
{
    $transactions = Transaction::with('orders')->get();

    $dataset = [];
    foreach ($transactions as $trx) {
        $dataset[] = $trx->orders->pluck('product_id')->unique()->toArray();
    }

    $minSupport = 2; 
    $result = $this->apriori($dataset, $minSupport);

    return response()->json([
        'message' => 'Apriori recommendation success',
        'data' => $result
    ]);
}


private function apriori($dataset, $minSupport)
{
    $itemset = [];
    foreach ($dataset as $transaction) {
        foreach ($transaction as $item) {
            $key = serialize([$item]);
            $itemset[$key] = isset($itemset[$key]) ? $itemset[$key] + 1 : 1;
        }
    }

    $allFrequent = [];

    // Level 1 frequent itemsets
    $frequent = array_filter($itemset, function($count) use ($minSupport) {
        return $count >= $minSupport;
    });
    $allFrequent += $frequent;

    // Generate next level itemsets
    while (!empty($frequent)) {
        $nextCandidate = [];

        $frequentKeys = array_keys($frequent);
        $count = count($frequentKeys);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $items1 = unserialize($frequentKeys[$i]);
                $items2 = unserialize($frequentKeys[$j]);

                $merged = array_unique(array_merge($items1, $items2));
                sort($merged);

                if (count($merged) == count($items1) + 1) {
                    $nextCandidate[serialize($merged)] = 0;
                }
            }
        }

        // Hitung support untuk kandidat berikutnya
        foreach ($dataset as $transaction) {
            $transaction = array_unique($transaction);
            foreach ($nextCandidate as $key => $val) {
                $candidate = unserialize($key);
                if (empty(array_diff($candidate, $transaction))) {
                    $nextCandidate[$key]++;
                }
            }
        }

        // Filter berdasarkan min support
        $frequent = array_filter($nextCandidate, function ($count) use ($minSupport) {
            return $count >= $minSupport;
        });
        $allFrequent += $frequent;
    }

    // Konversi ID ke nama produk
    $result = [];
    foreach ($allFrequent as $key => $count) {
        $productIds = unserialize($key);
        $products = Product::whereIn('id', $productIds)->pluck('name', 'id');

        $productNames = [];
        foreach ($productIds as $id) {
            $productNames[] = [
                'id' => $id,
                'name' => isset($products[$id]) ? $products[$id] : 'Unknown'
            ];
        }

        $result[] = [
            'items' => $productNames,
            'support' => $count
        ];
    }

    return $result;
}



}