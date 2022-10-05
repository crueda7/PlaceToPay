<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

Route::get('/product', [ProductController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('products.index');

Route::resource('shoppingCarts', ShoppingCartController::class)
    ->only(['index', 'create', 'store', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::post('/storeOrders', [OrderController::class, 'store'])
    ->name('shop.store');


Route::resource('orders', OrderController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified']);

Route::get('orders/checkout/{order}', [CheckoutController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('orders.checkout');

Route::post('orders/checkout/retry', [CheckoutController::class, 'retry'])
    ->middleware(['auth', 'verified'])
    ->name('orders.retry');

Route::post('orders/checkout/tryAgain', [CheckoutController::class, 'tryAgain'])
    ->middleware(['auth', 'verified'])
    ->name('orders.try');

require __DIR__ . '/auth.php';
