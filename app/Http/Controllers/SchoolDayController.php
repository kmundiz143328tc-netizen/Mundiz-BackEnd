<?php

namespace App\Http\Controllers;

use App\Models\SchoolDay;
use Illuminate\Http\Request;

class SchoolDayController extends Controller
{
    // GET /api/school-days?month=2024-06
    public function index(Request $request)
    {
        $query = SchoolDay::query()->orderBy('date');

        if ($request->has('month')) {
            $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$request->month]);
        }

        if ($request->has('year')) {
            $query->whereYear('date', $request->year);
        }

        return response()->json($query->get());
    }

    // POST /api/school-days
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'        => 'required|date|unique:school_days,date',
            'day_type'    => 'required|in:class,holiday,event,suspension',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'school_year' => 'nullable|string|max:20',
            'semester'    => 'nullable|string|max:10',
        ]);

        if (in_array($validated['day_type'], ['holiday', 'suspension'])) {
            $validated['attendance_rate']  = 0;
            $validated['students_present'] = 0;
            $validated['students_absent']  = 520;
        } else {
            $present = rand(440, 510);
            $validated['attendance_rate']  = round(($present / 520) * 100, 2);
            $validated['students_present'] = $present;
            $validated['students_absent']  = 520 - $present;
        }

        $validated['school_year'] = $validated['school_year'] ?? '2024-2025';
        $validated['semester']    = $validated['semester']    ?? '1st';

        $schoolDay = SchoolDay::create($validated);

        return response()->json($schoolDay, 201);
    }

    // GET /api/school-days/{id}
    public function show(SchoolDay $schoolDay)
    {
        return response()->json($schoolDay);
    }

    // PUT /api/school-days/{id}
    public function update(Request $request, SchoolDay $schoolDay)
    {
        $validated = $request->validate([
            'date'        => 'sometimes|date|unique:school_days,date,' . $schoolDay->id,
            'day_type'    => 'sometimes|in:class,holiday,event,suspension',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:500',
            'school_year' => 'nullable|string|max:20',
            'semester'    => 'nullable|string|max:10',
        ]);

        $schoolDay->update($validated);

        return response()->json($schoolDay);
    }

    // DELETE /api/school-days/{id}
    public function destroy(SchoolDay $schoolDay)
    {
        $schoolDay->delete();
        return response()->json(['message' => 'School day deleted successfully.']);
    }
}