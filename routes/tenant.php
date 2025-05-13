<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Tenant\LostAndFoundController;
use App\Http\Controllers\Tenant\StaffController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\CustomizeController;
use App\Http\Middleware\CheckTenantStatus;
use App\Http\Middleware\CustomizeMiddleware;  // Add this line

// Register the tenant routes
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Apply CheckTenantStatus after tenant initialization
    Route::middleware([CheckTenantStatus::class])->group(function () {
        // Your tenant routes

        // Apply the CustomizeMiddleware directly to the route group
        Route::middleware([CustomizeMiddleware::class])->group(function () {

            // Tenant Home Route
            Route::get('/', [TenantController::class, 'welcome'])->name('welcome');

            // Dashboard Route
            Route::get('/dashboard', [TenantController::class, 'dashboard'])->middleware('auth')->name('dashboard');

            // Authentication Routes
            Route::get('/login', [TenantController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [TenantController::class, 'login']);
            Route::post('/logout', [TenantController::class, 'logout'])->name('logout');

            // Lost and Found Routes
            Route::prefix('lostandfound')->middleware('auth')->group(function () {
                Route::get('/', [LostAndFoundController::class, 'index'])->name('lostandfound.index');
                Route::get('/create', [LostAndFoundController::class, 'create'])->name('lostandfound.create');
                Route::post('/', [LostAndFoundController::class, 'store'])->name('lostandfound.store');
                Route::get('/{lostAndFound}', [LostAndFoundController::class, 'show'])->name('lostandfound.show');
                Route::get('/{lostAndFound}/edit', [LostAndFoundController::class, 'edit'])->name('lostandfound.edit');
                Route::put('/{lostAndFound}', [LostAndFoundController::class, 'update'])->name('lostandfound.update');
                Route::delete('/{lostAndFound}', [LostAndFoundController::class, 'destroy'])->name('lostandfound.destroy');
                Route::post('/{lostAndFound}/claim', [LostAndFoundController::class, 'claim'])->name('lostandfound.claim');
            });

            // Staff Routes
            Route::prefix('staff')->middleware('auth')->group(function () {
                Route::get('/', [StaffController::class, 'index'])->name('staff.index');
                Route::get('/create', [StaffController::class, 'create'])->name('staff.create');
                Route::post('/', [StaffController::class, 'store'])->name('staff.store');
                Route::get('/{staff}', [StaffController::class, 'show'])->name('staff.show');
                Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
                Route::put('/{staff}', [StaffController::class, 'update'])->name('staff.update');
                Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
            });

            // Report Routes
            Route::prefix('reports')->middleware('auth')->group(function () {
                Route::get('/', [ReportController::class, 'index'])->name('report.index');
                // Add more report routes as needed
            });

            Route::post('/dashboard/update', [TenantController::class, 'updateTenant'])
    ->middleware('auth')
    ->name('tenant.dashboard.update');

            // Customize Routes
            Route::prefix('customize')->middleware('auth')->group(function () {
                Route::get('/', [CustomizeController::class, 'index'])->name('customize.index');
                Route::put('/', [CustomizeController::class, 'update'])->name('customize.update');  // PUT request route
                Route::put('reset', [CustomizeController::class, 'reset'])->name('customize.reset');

            });
        });

    });
});
