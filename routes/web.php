<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantRequestController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');  // Central domain welcome page
});
Route::get('/test', [TestController::class, 'index'])->name('test.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');   
});
 
Route::prefix('tenant/requests')->group(function () {
    Route::get('/', [TenantRequestController::class, 'index'])->name('tenants.index');
    
    Route::post('/', [TenantRequestController::class, 'store'])->name('tenant.requests.store');
    Route::patch('/{id}/approve', [TenantRequestController::class, 'approve'])->name('tenant.requests.approve');
    Route::put('/{id}', [TenantRequestController::class, 'update'])->name('tenant.requests.update');
    Route::delete('/{id}', [TenantRequestController::class, 'destroy'])->name('tenant.requests.destroy');
    Route::patch('/tenant/requests/{id}/reject', [TenantRequestController::class, 'reject'])->name('tenant.requests.reject');
    Route::patch('tenant/requests/{id}/disable', [TenantRequestController::class, 'disable'])->name('tenant.requests.disable');
    Route::patch('tenant/requests/{id}/enable', [TenantRequestController::class, 'enable'])->name('tenant.requests.enable');
});



require __DIR__.'/auth.php';