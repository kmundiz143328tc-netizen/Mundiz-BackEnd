<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // GET /api/students
    public function index(Request $request)
    {
        $query = Student::with('course')->orderBy('created_at', 'desc');

        // ✅ Filter by course_id (fixes "all students in every course" bug)
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or student_id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name',  'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email',      'like', "%{$search}%");
            });
        }

        // If per_page=500 or large, return all
        $perPage = $request->get('per_page', 10);
        if ($perPage >= 500) {
            return response()->json($query->get());
        }

        return response()->json($query->paginate($perPage));
    }

    // POST /api/students
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'      => 'required|string|unique:students,student_id',
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:students,email',
            'gender'          => 'in:Male,Female,Other',
            'department'      => 'nullable|string|max:255',
            'course_id'       => 'nullable|exists:courses,id',
            'year_level'      => 'nullable|integer|min:1|max:6',
            'enrollment_date' => 'nullable|date',
            'status'          => 'in:Active,Inactive,Graduated,Dropped',
            'age'             => 'nullable|integer',
            'address'         => 'nullable|string|max:500',
        ]);

        $student = Student::create($validated);

        ActivityLog::log('created', 'Student', "Added student: {$validated['first_name']} {$validated['last_name']} ({$validated['student_id']})");

        return response()->json($student->load('course'), 201);
    }

    // GET /api/students/{id}
    public function show(Student $student)
    {
        return response()->json($student->load('course'));
    }

    // PUT /api/students/{id}
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id'      => 'sometimes|string|unique:students,student_id,' . $student->id,
            'first_name'      => 'sometimes|string|max:255',
            'last_name'       => 'sometimes|string|max:255',
            'email'           => 'sometimes|email|unique:students,email,' . $student->id,
            'gender'          => 'in:Male,Female,Other',
            'department'      => 'nullable|string|max:255',
            'course_id'       => 'nullable|exists:courses,id',
            'year_level'      => 'nullable|integer|min:1|max:6',
            'enrollment_date' => 'nullable|date',
            'status'          => 'in:Active,Inactive,Graduated,Dropped',
            'age'             => 'nullable|integer',
            'address'         => 'nullable|string|max:500',
        ]);

        $student->update($validated);

        ActivityLog::log('updated', 'Student', "Updated student: {$student->first_name} {$student->last_name}");

        return response()->json($student->fresh('course'));
    }

    // DELETE /api/students/{id}
    public function destroy(Student $student)
    {
        $name = "{$student->first_name} {$student->last_name}";
        $student->delete();
        ActivityLog::log('deleted', 'Student', "Deleted student: {$name}");
        return response()->json(['message' => 'Student deleted.']);
    }

    // GET /api/students-by-department
    public function byDepartment()
    {
        $data = Student::select('department', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        return response()->json($data);
    }
}