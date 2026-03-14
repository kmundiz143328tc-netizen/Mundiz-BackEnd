<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'subject',
        'midterm',
        'finals',
        'gwa',
        'remarks',
        'school_year',
        'semester',
    ];

    protected $casts = [
        'midterm' => 'float',
        'finals'  => 'float',
        'gwa'     => 'float',
    ];

    // Auto-calculate GWA before saving
    protected static function booted()
    {
        static::saving(function ($grade) {
            if ($grade->midterm !== null && $grade->finals !== null) {
                $grade->gwa     = round(($grade->midterm + $grade->finals) / 2, 2);
                $grade->remarks = $grade->gwa >= 75 ? 'Passed' : 'Failed';
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}