<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// ---------- Authentication ------------



Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');


Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->with('error', 'Email atau password salah');
});


Route::get('/staff/dashboard', function () {
    return view('staff.dashboard-staff');
});

Route::get('/staff/cashier', function () {
    return view('staff.cashier');
})->name('staff.cashier');

Route::get('/staff/products', function () {
    return view('staff.products');
})->name('staff.products');

Route::get('/staff/reports', function () {
    return view('staff.staff-report');
})->name('staff.staff-report');


Route::get('/admin/products', function () {
    return view('admin.admin-products');
})->name('admin.admin-products');

Route::get('/admin/product/add', function () {
    return view('admin.admin-product-add');
})->name('admin.admin-product-add');