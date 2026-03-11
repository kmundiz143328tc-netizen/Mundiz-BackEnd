<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // College of Information Technology
            ['course_code' => 'BSIT',  'course_name' => 'BS Information Technology',     'department' => 'College of IT',        'units' => 3, 'instructor' => 'Prof. Juan Dela Cruz',  'max_students' => 50, 'description' => 'Core IT program covering software and systems.', 'schedule' => 'MWF 7:30-9:00'],
            ['course_code' => 'BSCS',  'course_name' => 'BS Computer Science',            'department' => 'College of IT',        'units' => 3, 'instructor' => 'Prof. Maria Santos',    'max_students' => 45, 'description' => 'Theory and practice of computing.', 'schedule' => 'TTH 10:30-12:00'],
            ['course_code' => 'BSIS',  'course_name' => 'BS Information Systems',         'department' => 'College of IT',        'units' => 3, 'instructor' => 'Prof. Jose Reyes',      'max_students' => 40, 'description' => 'Information systems design and management.', 'schedule' => 'MWF 1:00-2:30'],
            ['course_code' => 'ACT',   'course_name' => 'Associate in Computer Technology','department' => 'College of IT',       'units' => 3, 'instructor' => 'Prof. Ana Gomez',       'max_students' => 35, 'description' => '2-year tech program.', 'schedule' => 'TTH 7:30-9:00'],

            // College of Engineering
            ['course_code' => 'BSECE', 'course_name' => 'BS Electronics Engineering',    'department' => 'College of Engineering','units' => 3, 'instructor' => 'Engr. Carlos Ramos',  'max_students' => 40, 'description' => 'Electronics and communications engineering.', 'schedule' => 'MWF 10:30-12:00'],
            ['course_code' => 'BSCE',  'course_name' => 'BS Civil Engineering',           'department' => 'College of Engineering','units' => 3, 'instructor' => 'Engr. Rosa Lim',      'max_students' => 45, 'description' => 'Civil and structural engineering.', 'schedule' => 'TTH 1:00-2:30'],
            ['course_code' => 'BSME',  'course_name' => 'BS Mechanical Engineering',      'department' => 'College of Engineering','units' => 3, 'instructor' => 'Engr. Pedro Cruz',    'max_students' => 40, 'description' => 'Mechanical systems and design.', 'schedule' => 'MWF 2:30-4:00'],

            // College of Business
            ['course_code' => 'BSBA',  'course_name' => 'BS Business Administration',    'department' => 'College of Business',  'units' => 3, 'instructor' => 'Prof. Elena Torres',   'max_students' => 55, 'description' => 'General business management.', 'schedule' => 'TTH 7:30-9:00'],
            ['course_code' => 'BSACCT','course_name' => 'BS Accountancy',                 'department' => 'College of Business',  'units' => 3, 'instructor' => 'Prof. Rafael Garcia',  'max_students' => 50, 'description' => 'Financial accounting and auditing.', 'schedule' => 'MWF 9:00-10:30'],
            ['course_code' => 'BSMKT', 'course_name' => 'BS Marketing Management',        'department' => 'College of Business',  'units' => 3, 'instructor' => 'Prof. Luisa Fernandez','max_students' => 45, 'description' => 'Marketing strategy and consumer behavior.', 'schedule' => 'TTH 10:30-12:00'],

            // College of Education
            ['course_code' => 'BEED',  'course_name' => 'BS Elementary Education',        'department' => 'College of Education', 'units' => 3, 'instructor' => 'Prof. Carmen Rivera',  'max_students' => 40, 'description' => 'Teacher education for elementary grades.', 'schedule' => 'MWF 7:30-9:00'],
            ['course_code' => 'BSED',  'course_name' => 'BS Secondary Education',         'department' => 'College of Education', 'units' => 3, 'instructor' => 'Prof. Domingo Navarro','max_students' => 40, 'description' => 'Teacher education for secondary grades.', 'schedule' => 'TTH 1:00-2:30'],

            // College of Nursing & Health Sciences
            ['course_code' => 'BSN',   'course_name' => 'BS Nursing',                     'department' => 'College of Nursing',   'units' => 3, 'instructor' => 'Prof. Grace Villanueva','max_students' => 45, 'description' => 'Professional nursing education.', 'schedule' => 'MWF 10:30-12:00'],
            ['course_code' => 'BSMT',  'course_name' => 'BS Medical Technology',          'department' => 'College of Nursing',   'units' => 3, 'instructor' => 'Prof. Albert Tan',     'max_students' => 35, 'description' => 'Medical laboratory science.', 'schedule' => 'TTH 7:30-9:00'],
            ['course_code' => 'BSPT',  'course_name' => 'BS Physical Therapy',            'department' => 'College of Nursing',   'units' => 3, 'instructor' => 'Prof. Sandra Lopez',   'max_students' => 30, 'description' => 'Physical rehabilitation and therapy.', 'schedule' => 'MWF 1:00-2:30'],

            // College of Arts & Sciences
            ['course_code' => 'ABCOMM','course_name' => 'AB Communication',               'department' => 'College of Arts',      'units' => 3, 'instructor' => 'Prof. Nora Castillo',  'max_students' => 40, 'description' => 'Mass communication and journalism.', 'schedule' => 'TTH 2:30-4:00'],
            ['course_code' => 'ABPSY', 'course_name' => 'AB Psychology',                  'department' => 'College of Arts',      'units' => 3, 'instructor' => 'Prof. Victor Morales', 'max_students' => 45, 'description' => 'Human behavior and mental processes.', 'schedule' => 'MWF 9:00-10:30'],
            ['course_code' => 'BSMATH','course_name' => 'BS Mathematics',                 'department' => 'College of Arts',      'units' => 3, 'instructor' => 'Prof. Irene Soriano',  'max_students' => 30, 'description' => 'Pure and applied mathematics.', 'schedule' => 'TTH 10:30-12:00'],

            // College of Tourism
            ['course_code' => 'BSHM',  'course_name' => 'BS Hospitality Management',      'department' => 'College of Tourism',   'units' => 3, 'instructor' => 'Prof. Marisol Aquino', 'max_students' => 45, 'description' => 'Hotel and restaurant management.', 'schedule' => 'MWF 2:30-4:00'],
            ['course_code' => 'BSTM',  'course_name' => 'BS Tourism Management',          'department' => 'College of Tourism',   'units' => 3, 'instructor' => 'Prof. Felix Domingo',  'max_students' => 40, 'description' => 'Tourism industry and travel management.', 'schedule' => 'TTH 7:30-9:00'],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(['course_code' => $course['course_code']], $course);
        }

        $this->command->info('✅ CourseSeeder: 20 courses seeded successfully.');
    }
}