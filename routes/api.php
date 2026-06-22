<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\MemberAuthController;
use App\Http\Controllers\Member\MemberForgotPasswordController;
use App\Http\Controllers\Member\MemberProfileController;

Route::prefix('member')->group(function () {

    Route::post('/login', [MemberAuthController::class, 'login']);

    Route::middleware('auth:member')->group(function () {
        Route::get('/profile', [MemberAuthController::class, 'profile']);
        Route::post('/logout', [MemberAuthController::class, 'logout']);
        Route::post('/update-profile-photo', [MemberProfileController::class, 'updateProfilePhoto']);
    });
    Route::post('/forgot-password', [MemberForgotPasswordController::class, 'forgotPassword']);
    Route::post('/verify-otp', [MemberForgotPasswordController::class, 'verifyOtp']);
    Route::post('/reset-password', [MemberForgotPasswordController::class, 'resetPassword']);
    
});