<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\SchoolDay;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get all dashboard summary statistics.
     */
    public function stats()
    {
        $totalStudents    = Student::count();
        $activeStudents   = Student::where('status', 'Active')->count();
        $totalCourses     = Course::count();
        $totalSchoolDays  = SchoolDay::where('day_type', 'class')->count();
        $avgAttendance    = SchoolDay::where('day_type', 'class')->avg('attendance_rate');

        return response()->json([
            'total_students'   => $totalStudents,
            'active_students'  => $activeStudents,
            'total_courses'    => $totalCourses,
            'total_school_days'=> $totalSchoolDays,
            'avg_attendance'   => round($avgAttendance, 2),
        ]);
    }

    /**
     * Monthly enrollment data for Bar Chart.
     */
    public function enrollmentTrends()
    {
        $data = Student::selectRaw("DATE_FORMAT(enrollment_date, '%b %Y') as month,
                                    DATE_FORMAT(enrollment_date, '%Y-%m') as sort_key,
                                    COUNT(*) as students")
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get()
            ->map(fn($item) => [
                'month'    => $item->month,
                'students' => $item->students,
            ]);

        return response()->json($data);
    }

    /**
     * Student distribution per course for Pie Chart.
     */
    public function courseDistribution()
    {
        $data = Course::withCount('students')
            ->orderByDesc('students_count')
            ->get()
            ->map(fn($c) => [
                'name'  => $c->course_code,
                'label' => $c->course_name,
                'value' => $c->students_count,
            ]);

        return response()->json($data);
    }

    /**
     * Attendance over school days for Line Chart.
     */
    public function attendanceTrends()
    {
        $data = SchoolDay::where('day_type', 'class')
            ->orderBy('date')
            ->get()
            ->map(fn($day) => [
                'date'            => $day->date->format('M d'),
                'attendance_rate' => $day->attendance_rate,
                'present'         => $day->students_present,
                'absent'          => $day->students_absent,
            ]);

        return response()->json($data);
    }

    /**
     * Upcoming events and holidays.
     */
    public function calendar()
    {
        $data = SchoolDay::whereIn('day_type', ['holiday', 'event'])
            ->orderBy('date')
            ->take(20)
            ->get();

        return response()->json($data);
    }
}