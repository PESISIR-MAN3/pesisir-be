<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationMethodController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/activities', [ActivityController::class, 'index']);
Route::get('/activities/{id}', [ActivityController::class, 'show']);
Route::post('/activities', [ActivityController::class, 'store']);
Route::put('/activities/{id}', [ActivityController::class, 'update']);
Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);

Route::get('/donations', [DonationController::class, 'index']);
Route::post('/donations', [DonationController::class, 'store']);
Route::get('/donations/{id}', [DonationController::class, 'show']);
Route::delete('/donations/{id}', [DonationController::class, 'destroy']);

Route::get('/donation-methods', [DonationMethodController::class, 'index']);
Route::get('/donation-methods/{id}', [DonationMethodController::class, 'show']);
Route::post('/donation-methods', [DonationMethodController::class, 'store']);
Route::put('/donation-methods/{id}', [DonationMethodController::class, 'update']);
Route::delete('/donation-methods/{id}', [DonationMethodController::class, 'destroy']);

Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{id}', [LocationController::class, 'show']);
Route::post('/locations', [LocationController::class, 'store']);
Route::put('/locations/{id}', [LocationController::class, 'update']);
Route::delete('/locations/{id}', [LocationController::class, 'destroy']);

Route::get('/complaints', [ComplaintController::class, 'index']);
Route::post('/complaints', [ComplaintController::class, 'store']);
Route::get('/complaints/{id}', [ComplaintController::class, 'show']);
Route::delete('/complaints/{id}',[ComplaintController::class, 'destroy']);

Route::get('/volunteers', [VolunteerController::class, 'index']);
Route::post('/volunteers', [VolunteerController::class, 'store']);
Route::get('/volunteers/{id}', [VolunteerController::class, 'show']);
Route::delete('/volunteers', [VolunteerController::class, 'destroy']);
