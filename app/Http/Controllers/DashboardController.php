<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\SchoolDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_students'    => Student::count(),
            'active_students'   => Student::where('status', 'Active')->count(),
            'total_courses'     => Course::count(),
            'total_school_days' => SchoolDay::where('day_type', 'class')->count(),
            'avg_attendance'    => round(
                SchoolDay::where('day_type', 'class')->avg('attendance_rate') ?? 0, 1
            ),
        ]);
    }

    public function enrollmentTrends()
    {
        try {
            return response()->json(
                Student::select(
                    DB::raw("DATE_FORMAT(enrollment_date, '%b %Y') as month"),
                    DB::raw("DATE_FORMAT(enrollment_date, '%Y-%m') as sort_key"),
                    DB::raw('COUNT(*) as students')
                )
                ->whereNotNull('enrollment_date')
                ->groupBy('month', 'sort_key')
                ->orderBy('sort_key')
                ->get()
                ->map(fn($r) => ['month' => $r->month, 'students' => $r->students])
            );
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function courseDistribution()
    {
        return response()->json(
            Student::select('course_id', DB::raw('COUNT(*) as value'))
                ->with('course:id,course_name,course_code')
                ->whereNotNull('course_id')
                ->groupBy('course_id')
                ->orderByDesc('value')
                ->get()
                ->map(fn($s) => [
                    'name'  => $s->course?->course_code ?? 'Unknown',
                    'label' => $s->course?->course_name ?? 'Unknown',
                    'value' => (int) $s->value,
                ])
        );
    }

    public function attendanceTrends()
    {
        return response()->json(
            SchoolDay::where('day_type', 'class')
                ->select('date', 'attendance_rate', 'students_present', 'students_absent')
                ->orderBy('date')
                ->get()
        );
    }

    public function calendar()
    {
        return response()->json(
            SchoolDay::whereIn('day_type', ['holiday', 'event', 'suspension'])
                ->orderBy('date')
                ->get()
        );
    }

    public function departmentStats()
    {
        return response()->json(
            Student::select(
                'department',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status='Active' THEN 1 ELSE 0 END) as active"),
                DB::raw("SUM(CASE WHEN status='Graduated' THEN 1 ELSE 0 END) as graduated")
            )
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderByDesc('total')
            ->get()
        );
    }
}