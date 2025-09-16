<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/activities', [ActivityController::class, 'index']);
Route::get('/activities/{id}/volunteers', [ActivityController::class, 'volunteers']);
Route::post('/activities', [ActivityController::class, 'store']);
Route::put('/activities/{id}', [ActivityController::class, 'update']);
Route::get('/activities/{id}', [ActivityController::class, 'show']);
Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);

Route::get('/reports', [ReportController::class, 'index']);
Route::get('/reports/{id}', [ReportController::class, 'show']);
Route::post('/reports', [ReportController::class, 'store']);
Route::delete('/reports/{id}',[ReportController::class, 'destroy']);

Route::get('/volunteers', [VolunteerController::class, 'index']);
Route::post('/volunteers', [VolunteerController::class, 'store']);