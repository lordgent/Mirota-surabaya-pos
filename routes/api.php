<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/signin', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

});

// service API hanya bisa di akses oleh role admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/category', [CategoryController::class, 'store']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/admin/products', [ProductController::class, 'index']);


});

// service API hanya bisa di akses oleh role manager atau admin
Route::middleware(['auth:sanctum', 'role:manager,admin'])->group(function () {

});


// service API hanya bisa di akses oleh role staff
Route::middleware(['auth:sanctum', 'role:staff'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/transaction', [TransactionController::class, 'createTransactionUser']);
    Route::get('/recommendation', [TransactionController::class, 'aprioriRecommendation']);
    Route::get('/transactions', [TransactionController::class, 'getTransactionList']);

    
});
