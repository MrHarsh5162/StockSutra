<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/morning-stock', function () { return view('admin.morning-stock.index'); })->name('morning-stock.index');
        Route::get('/night-closing', function () { return view('admin.night-closing.index'); })->name('night-closing.index');
        Route::get('/stock-report', function () { return view('admin.stock-report.index'); })->name('stock-report.index');
        Route::get('/order-items', function () { return view('admin.order-items.index'); })->name('order-items.index');

        // Product Management
        Route::get('/categories', function () { return view('admin.products.categories.index'); })->name('categories.index');
        Route::get('/units', function () { return view('admin.products.units.index'); })->name('units.index');
        Route::get('/items', function () { return view('admin.products.items.index'); })->name('items.index');
    });
});
