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
}