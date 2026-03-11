<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Protected Routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout',  [AuthController::class, 'logout']);
    Route::get('/auth/profile',  [AuthController::class, 'profile']);

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats',               [DashboardController::class, 'stats']);
        Route::get('/enrollment-trends',   [DashboardController::class, 'enrollmentTrends']);
        Route::get('/course-distribution', [DashboardController::class, 'courseDistribution']);
        Route::get('/attendance-trends',   [DashboardController::class, 'attendanceTrends']);
        Route::get('/calendar',            [DashboardController::class, 'calendar']);
    });

    // Students
    Route::apiResource('students', StudentController::class);
    Route::get('/students-by-department', [StudentController::class, 'byDepartment']);

    // Courses
    Route::apiResource('courses', CourseController::class);
    Route::get('/courses-distribution',   [CourseController::class, 'distribution']);
});