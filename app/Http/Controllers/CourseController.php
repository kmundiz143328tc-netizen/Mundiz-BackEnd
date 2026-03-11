<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('students')->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code'  => 'required|unique:courses',
            'course_name'  => 'required|string|max:255',
            'department'   => 'required|string',
            'units'        => 'required|integer|between:1,6',
            'description'  => 'nullable|string',
            'instructor'   => 'required|string',
            'max_students' => 'required|integer|min:1',
            'schedule'     => 'nullable|string',
        ]);

        $course = Course::create($validated);
        return response()->json($course, 201);
    }

    public function show(Course $course)
    {
        return response()->json($course->load('students'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_name'  => 'sometimes|string|max:255',
            'department'   => 'sometimes|string',
            'units'        => 'sometimes|integer|between:1,6',
            'description'  => 'nullable|string',
            'instructor'   => 'sometimes|string',
            'max_students' => 'sometimes|integer|min:1',
            'schedule'     => 'nullable|string',
        ]);

        $course->update($validated);
        return response()->json($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }

    /**
     * Get student distribution across courses (for pie chart).
     */
    public function distribution()
    {
        $data = Course::withCount('students')
            ->orderByDesc('students_count')
            ->get()
            ->map(fn($c) => [
                'name'  => $c->course_name,
                'code'  => $c->course_code,
                'value' => $c->students_count,
            ]);

        return response()->json($data);
    }
}