<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'email',
        'gender',
        'department',
        'course_id',
        'year_level',
        'enrollment_date',
        'status',
        'age',
        'address',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    /**
     * Get the course the student is enrolled in.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}