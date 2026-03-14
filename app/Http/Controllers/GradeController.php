<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    // GET /api/grades?student_id=1
    public function index(Request $request)
    {
        $query = Grade::with(['student', 'course'])->orderBy('created_at', 'desc');

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        return response()->json($query->get());
    }

    // POST /api/grades
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'course_id'   => 'required|exists:courses,id',
            'subject'     => 'required|string|max:255',
            'midterm'     => 'nullable|numeric|min:0|max:100',
            'finals'      => 'nullable|numeric|min:0|max:100',
            'school_year' => 'nullable|string',
            'semester'    => 'nullable|string',
        ]);

        $grade = Grade::create($validated);

        ActivityLog::log('created', 'Grade', "Added grade for student ID {$validated['student_id']} — {$validated['subject']}");

        return response()->json($grade->load(['student', 'course']), 201);
    }

    // PUT /api/grades/{id}
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'subject'     => 'sometimes|string|max:255',
            'midterm'     => 'nullable|numeric|min:0|max:100',
            'finals'      => 'nullable|numeric|min:0|max:100',
            'school_year' => 'nullable|string',
            'semester'    => 'nullable|string',
        ]);

        $grade->update($validated);

        ActivityLog::log('updated', 'Grade', "Updated grade ID {$grade->id}");

        return response()->json($grade->fresh(['student', 'course']));
    }

    // DELETE /api/grades/{id}
    public function destroy(Grade $grade)
    {
        ActivityLog::log('deleted', 'Grade', "Deleted grade ID {$grade->id}");
        $grade->delete();
        return response()->json(['message' => 'Grade deleted.']);
    }

    // GET /api/grades/summary?student_id=1 — GWA summary per student
    public function summary(Request $request)
    {
        $request->validate(['student_id' => 'required|exists:students,id']);

        $grades = Grade::where('student_id', $request->student_id)->get();

        $passed   = $grades->where('remarks', 'Passed')->count();
        $failed   = $grades->where('remarks', 'Failed')->count();
        $totalGwa = $grades->whereNotNull('gwa')->avg('gwa');

        return response()->json([
            'total_subjects' => $grades->count(),
            'passed'         => $passed,
            'failed'         => $failed,
            'overall_gwa'    => $totalGwa ? round($totalGwa, 2) : null,
            'standing'       => $totalGwa ? ($totalGwa >= 75 ? 'Good Standing' : 'At Risk') : 'No Grades Yet',
            'grades'         => $grades,
        ]);
    }
}