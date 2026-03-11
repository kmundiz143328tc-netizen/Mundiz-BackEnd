<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'day_type',       // 'class', 'holiday', 'event', 'suspension'
        'title',
        'description',
        'attendance_rate', // percentage 0-100
        'students_present',
        'students_absent',
        'school_year',
        'semester',
    ];

    protected $casts = [
        'date' => 'date',
        'attendance_rate' => 'float',
    ];
}