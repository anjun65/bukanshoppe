<?php

use Illuminate\Support\Facades\Route;

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
})->name('home');

Route::get('/product', function () {
    return view('product');
})->name('product');

Route::get('/product/{id}', function () {
    return view('product');
})->name('product-details');

Route::get('/category', function () {
    return view('category');
})->name('category');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/admin', function () {
    return view('admin.home');
})->name('admin');

Route::get('/admin/categories', function () {
    return view('admin.categories');
})->name('admin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});