<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/activities', [ActivityController::class, 'index']);
Route::get('/activities/{id}/volunteers', [ActivityController::class, 'volunteers']); // Cek semua volunteer untuk satu activity
Route::get('/activities/{id}', [ActivityController::class, 'show']);
Route::post('/activities', [ActivityController::class, 'store']);
Route::put('/activities/{id}', [ActivityController::class, 'update']);
Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);

Route::get('/donations', [DonationController::class, 'index']);
Route::get('/donations/{id}', [DonationController::class, 'show']);
Route::post('/donations', [DonationController::class, 'store']);
Route::delete('/donations/{id}', [DonationController::class, 'destroy']);

Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{id}', [LocationController::class, 'show']);
Route::post('/locations', [LocationController::class, 'store']);
Route::put('/locations/{id}', [LocationController::class, 'update']);
Route::delete('/locations/{id}', [LocationController::class, 'destroy']);

Route::get('/reports', [ReportController::class, 'index']);
Route::get('/reports/{id}', [ReportController::class, 'show']);
Route::post('/reports', [ReportController::class, 'store']);
Route::delete('/reports/{id}',[ReportController::class, 'destroy']);

Route::get('/volunteers', [VolunteerController::class, 'index']);
Route::get('/volunteers/{id}', [VolunteerController::class, 'show']);
Route::post('/volunteers', [VolunteerController::class, 'store']);
Route::delete('/volunteers', [VolunteerController::class, 'destroy']);