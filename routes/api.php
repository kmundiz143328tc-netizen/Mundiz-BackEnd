<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolDayController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;

// ─── Public Auth Routes ───────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// ─── Protected Routes (require Sanctum token) ─────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::get('/profile',  [AuthController::class, 'profile']);
    });

    // Profile update
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats',               [DashboardController::class, 'stats']);
        Route::get('/enrollment-trends',   [DashboardController::class, 'enrollmentTrends']);
        Route::get('/course-distribution', [DashboardController::class, 'courseDistribution']);
        Route::get('/attendance-trends',   [DashboardController::class, 'attendanceTrends']);
        Route::get('/calendar',            [DashboardController::class, 'calendar']);
        Route::get('/department-stats',    [DashboardController::class, 'departmentStats']);
    });

    // Students
    Route::apiResource('students', StudentController::class);
    Route::get('/students-by-department', [StudentController::class, 'byDepartment']);

    // Courses
    Route::apiResource('courses', CourseController::class);
    Route::get('/courses-distribution', [CourseController::class, 'distribution']);

    // School Days
    Route::apiResource('school-days', SchoolDayController::class);

    // Grades
    Route::get('/grades/summary', [GradeController::class, 'summary']);
    Route::apiResource('grades', GradeController::class)->except(['show']);

    // Announcements
    Route::apiResource('announcements', AnnouncementController::class)->except(['show']);

    // Activity Logs
    Route::get('/activity-logs',         [ActivityLogController::class, 'index']);
    Route::delete('/activity-logs/clear', [ActivityLogController::class, 'clear']);

    // Notifications — upcoming events + recent announcements (next 7 days)
    Route::get('/notifications', function () {
        $upcoming = \App\Models\SchoolDay::where('date', '>=', now())
            ->where('date', '<=', now()->addDays(7))
            ->whereIn('day_type', ['holiday', 'event', 'suspension'])
            ->orderBy('date')
            ->get()
            ->map(fn($d) => [
                'id'      => 'day_' . $d->id,
                'type'    => $d->day_type,
                'title'   => $d->title,
                'message' => 'On ' . \Carbon\Carbon::parse($d->date)->format('M d, Y'),
                'date'    => $d->date,
            ]);

        $announcements = \App\Models\Announcement::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($a) => [
                'id'       => 'ann_' . $a->id,
                'type'     => 'announcement',
                'title'    => $a->title,
                'message'  => \Illuminate\Support\Str::limit($a->content, 60),
                'date'     => $a->created_at,
                'priority' => $a->priority,
            ]);

        return response()->json(
            $upcoming->concat($announcements)->sortByDesc('date')->values()
        );
    });
});