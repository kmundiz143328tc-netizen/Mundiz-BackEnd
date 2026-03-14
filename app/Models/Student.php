<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id', 'first_name', 'last_name', 'email',
        'gender', 'department', 'course_id', 'year_level',
        'enrollment_date', 'status', 'age', 'address',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'year_level'      => 'integer',
        'age'             => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}