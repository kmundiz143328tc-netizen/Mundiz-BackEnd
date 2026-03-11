<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'course_name',
        'department',
        'units',
        'description',
        'instructor',
        'max_students',
        'schedule',
    ];

    /**
     * Get students enrolled in this course.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}