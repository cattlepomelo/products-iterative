<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products')->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
    Route::resource('products', ProductController::class);
    Route::patch('/products/{product}/quantity', [ProductController::class, 'updateQuantity'])
        ->name('products.quantity.update');
    Route::put('/products/{product}/tags', [TagController::class, 'update'])
        ->name('products.tags.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
