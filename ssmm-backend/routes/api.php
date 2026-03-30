<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Student\ServiceRequestController;
use App\Http\Controllers\Api\Student\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('students', StudentController::class); //Crud Students

    Route::prefix('student-requests')->group(function () {
        Route::get('/', [ServiceRequestController::class, 'index']); // List with filters
        Route::get('{id}', [ServiceRequestController::class, 'show']); // Show specific request
        Route::post('/', [ServiceRequestController::class, 'store']); // Create request
        Route::patch('{id}/status', [ServiceRequestController::class, 'updateStatus']); // Approve/Reject
        Route::delete('{id}', [ServiceRequestController::class, 'destroy']); // Admin delete
        Route::post('/upload', [ServiceRequestController::class, 'upload']); //  upload

    });
});
