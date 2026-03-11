<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $courses     = Course::all();
        $courseIds   = $courses->pluck('id')->toArray();
        $departments = $courses->pluck('department', 'id')->toArray();

        $firstNames = [
            'Male'   => ['Juan', 'Jose', 'Carlos', 'Miguel', 'Antonio', 'Roberto', 'Eduardo', 'Fernando', 'Ricardo', 'Marcos', 'Angelo', 'Lester', 'Ronnie', 'Dennis', 'Kevin', 'John', 'Michael', 'James', 'Daniel', 'Christian', 'Ryan', 'Mark', 'Joshua', 'Paul', 'Jerome'],
            'Female' => ['Maria', 'Ana', 'Rosa', 'Elena', 'Carmen', 'Luisa', 'Clara', 'Angela', 'Patricia', 'Sandra', 'Jennifer', 'Michelle', 'Christine', 'Lovely', 'Maricel', 'Grace', 'Faith', 'Joy', 'Hope', 'Precious', 'Rhea', 'Karen', 'Jasmine', 'Nicole', 'Camille'],
        ];
        $lastNames = ['Dela Cruz', 'Santos', 'Reyes', 'Garcia', 'Torres', 'Ramos', 'Lim', 'Cruz', 'Fernandez', 'Rivera', 'Navarro', 'Villanueva', 'Tan', 'Lopez', 'Castillo', 'Morales', 'Soriano', 'Aquino', 'Domingo', 'Bautista', 'Mendoza', 'Flores', 'Gonzales', 'Perez', 'Diaz', 'Manalo', 'Valdez', 'Pascual', 'Salazar', 'Aguilar'];
        $statuses  = ['Active', 'Active', 'Active', 'Active', 'Active', 'Inactive', 'Dropped', 'Graduated'];
        $addresses = ['Davao City', 'Tagum City', 'Digos City', 'General Santos', 'Butuan City', 'Cagayan de Oro', 'Cotabato City', 'Zamboanga City', 'Iligan City', 'Dipolog City'];

        // Enrollment dates spread over 2 years for realistic chart data
        $startDate = strtotime('2023-06-01');
        $endDate   = strtotime('2025-05-31');

        $students = [];
        $usedEmails = [];
        $usedIds    = [];
        $count = 0;

        while ($count < 520) {
            $gender      = array_rand(['Male' => 0, 'Female' => 1]) === 0 ? 'Male' : 'Female';
            $genderKey   = ($gender === 'Male') ? 'Male' : 'Female';
            $firstName   = $firstNames[$genderKey][array_rand($firstNames[$genderKey])];
            $lastName    = $lastNames[array_rand($lastNames)];
            $courseId    = $courseIds[array_rand($courseIds)];
            $department  = $departments[$courseId];
            $yearLevel   = rand(1, 4);

            // Generate unique student ID
            do {
                $studentId = 'STU-' . rand(2020, 2024) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (in_array($studentId, $usedIds));
            $usedIds[] = $studentId;

            // Generate unique email
            do {
                $emailSuffix = rand(1, 9999);
                $email = strtolower($firstName . '.' . str_replace(' ', '', $lastName) . $emailSuffix . '@school.edu.ph');
            } while (in_array($email, $usedEmails));
            $usedEmails[] = $email;

            // Random enrollment date
            $enrollDate   = date('Y-m-d', rand($startDate, $endDate));
            $status       = $statuses[array_rand($statuses)];

            $students[] = [
                'student_id'      => $studentId,
                'first_name'      => $firstName,
                'last_name'       => $lastName,
                'email'           => $email,
                'gender'          => $gender,
                'department'      => $department,
                'course_id'       => $courseId,
                'year_level'      => $yearLevel,
                'enrollment_date' => $enrollDate,
                'status'          => $status,
                'age'             => rand(17, 28),
                'address'         => $addresses[array_rand($addresses)],
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            $count++;
        }

        // Insert in chunks for performance
        foreach (array_chunk($students, 100) as $chunk) {
            DB::table('students')->insert($chunk);
        }

        $this->command->info('✅ StudentSeeder: 520 student records seeded successfully.');
    }
}