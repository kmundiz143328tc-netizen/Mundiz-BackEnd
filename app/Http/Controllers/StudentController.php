<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a paginated listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::with('course');

        // Filter by department
        if ($request->has('department')) {
            $query->where('department', $request->department);
        }

        // Filter by year level
        if ($request->has('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or student_id
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate($request->get('per_page', 15));

        return response()->json($students);
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'      => 'required|unique:students',
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email|unique:students',
            'gender'          => 'required|in:Male,Female,Other',
            'department'      => 'required|string',
            'course_id'       => 'required|exists:courses,id',
            'year_level'      => 'required|integer|between:1,6',
            'enrollment_date' => 'required|date',
            'status'          => 'required|in:Active,Inactive,Graduated,Dropped',
            'age'             => 'required|integer|min:15|max:60',
            'address'         => 'nullable|string',
        ]);

        $student = Student::create($validated);
        return response()->json($student->load('course'), 201);
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        return response()->json($student->load('course'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name'      => 'sometimes|string|max:100',
            'last_name'       => 'sometimes|string|max:100',
            'email'           => 'sometimes|email|unique:students,email,' . $student->id,
            'gender'          => 'sometimes|in:Male,Female,Other',
            'department'      => 'sometimes|string',
            'course_id'       => 'sometimes|exists:courses,id',
            'year_level'      => 'sometimes|integer|between:1,6',
            'enrollment_date' => 'sometimes|date',
            'status'          => 'sometimes|in:Active,Inactive,Graduated,Dropped',
            'age'             => 'sometimes|integer|min:15|max:60',
            'address'         => 'nullable|string',
        ]);

        $student->update($validated);
        return response()->json($student->load('course'));
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }

    /**
     * Get enrollment statistics by month.
     */
    public function enrollmentByMonth()
    {
        $data = Student::selectRaw("DATE_FORMAT(enrollment_date, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }

    /**
     * Get student count per department.
     */
    public function byDepartment()
    {
        $data = Student::selectRaw('department, COUNT(*) as count')
            ->groupBy('department')
            ->get();

        return response()->json($data);
    }
}