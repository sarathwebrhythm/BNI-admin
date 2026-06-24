<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ExcelController;
use App\Http\Controllers\Admin\OfferCategoryController;
use Illuminate\Support\Facades\Mail;

Route::redirect('/', '/admin/dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
        
        Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPassword'])->name('forgot-password');
        Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLink']);
        
        Route::get('/reset-password/{token}', [AdminAuthController::class, 'showResetPassword'])->name('reset-password.show');
        Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('reset-password.update');
    });

    // Authenticated Admin Routes
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Members CRUD
        Route::resource('members', MemberController::class);
        
        // Excel Import / Export
        Route::get('/import', [ExcelController::class, 'showImport'])->name('import.show');
        Route::post('/import', [ExcelController::class, 'import'])->name('import.store');
        Route::get('/export', [ExcelController::class, 'export'])->name('export');
        Route::get('/export-template', [ExcelController::class, 'downloadTemplate'])->name('export-template');
        // for Offer Categories
        Route::resource('offer-categories', OfferCategoryController::class);
    });
});