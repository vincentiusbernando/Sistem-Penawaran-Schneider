<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerLoginController;
use App\Http\Controllers\InternalLoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\StandardDiscountController;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\InternalMiddleware;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Login Routes
Route::post('/api/login_internal', [InternalLoginController::class, 'authenticate']);
Route::post('/api/login_customer', [CustomerLoginController::class, 'authenticate']);

// Routes accessible to customers only
Route::middleware(CustomerMiddleware::class)->group(function () {
    Route::get('/customer', [PenawaranController::class, 'customer'])->name('customer');
    Route::get('/customer/{id}', [PenawaranController::class, 'show_customer']);
});

// Routes accessible to internal users only
Route::middleware([InternalMiddleware::class])->group(function () {
    Route::get('/penawaran_baru', [PenawaranController::class, 'penawaran_baru']);
    Route::get('/api/internal', [PenawaranController::class, 'index']);
    Route::get('/api/internal/{by}/{query}', [PenawaranController::class, 'search']);
    Route::get('/api/internal/{uri}', [PenawaranController::class, 'show']);
    Route::post('/api/buat_penawaran', [PenawaranController::class, 'store']);
    Route::post('/api/update_penawaran', [PenawaranController::class, 'update']);
    Route::post('/api/dashboard', [PenawaranController::class, 'dashboard']);
    Route::post('/api/summary', [PenawaranController::class, 'summary']);


    Route::get('/api/perusahaan', [PerusahaanController::class, 'index']);
    Route::post('/api/buat_perusahaan', [PerusahaanController::class, 'store']);

    Route::post('/api/buat_customer', [CustomerController::class, 'store']);
    Route::post('/api/upload_customer', [CustomerController::class, 'upload']);



    Route::get('/api/product', [ProductController::class, 'index']);
    Route::get('/api/search_product', [ProductController::class, 'search_ref']);
    Route::get('/api/product/stock/{id}', [ProductController::class, 'stock']);
    Route::get('/api/product/{by}/{query}', [ProductController::class, 'search_product']);
    Route::get('/api/product/{ref}', [ProductController::class, 'edit']);
    Route::post('/api/edit_product', [ProductController::class, 'update']);
    Route::post('/api/buat_product', [ProductController::class, 'store']);
    Route::post('/api/upload_product', [ProductController::class, 'upload']);
    Route::post('/api/update_stock', [ProductController::class, 'update_stock']);


    Route::get('/api/sd', [StandardDiscountController::class, 'index']);


    Route::get('/api/search_customer', [CustomerController::class, 'search']);
});
