<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\MemberAuthController;
use App\Http\Controllers\Member\MemberForgotPasswordController;
use App\Http\Controllers\Member\MemberProfileController;
use App\Http\Controllers\Member\OfferController;

Route::prefix('member')->group(function () {

    Route::post('/login', [MemberAuthController::class, 'login']);

    Route::middleware('auth:member')->group(function () {
        Route::get('/profile', [MemberAuthController::class, 'profile']);
        Route::post('/logout', [MemberAuthController::class, 'logout']);
        Route::post('/update-profile-photo', [MemberProfileController::class, 'updateProfilePhoto']);
        Route::post('/update-cover-photo', [MemberProfileController::class, 'updateCoverPhoto']);
        Route::post('/update-business-logo', [MemberProfileController::class, 'updateBusinessLogo']);
        // Offer routes 
        Route::get('/offers', [OfferController::class, 'index']);
        Route::post('/offers', [OfferController::class, 'store']);
        Route::delete('/offers/{id}', [OfferController::class, 'destroy']);
        Route::get('/offer-categories', [OfferController::class, 'categories']);
        // For all offers (active) for members to view
         Route::get('/all-offers', [OfferController::class, 'allActive']);
    });
    Route::post('/forgot-password', [MemberForgotPasswordController::class, 'forgotPassword']);
    Route::post('/verify-otp', [MemberForgotPasswordController::class, 'verifyOtp']);
    Route::post('/reset-password', [MemberForgotPasswordController::class, 'resetPassword']);
});
