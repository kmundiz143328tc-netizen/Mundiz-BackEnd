<?php

namespace Database\Seeders;

use App\Models\SchoolDay;
use Illuminate\Database\Seeder;

class SchoolDaySeeder extends Seeder
{
    public function run(): void
    {
        $totalStudents = 520;

        $holidays = [
            '2024-06-12' => ['title' => 'Independence Day',       'description' => 'Philippine Independence Day'],
            '2024-08-21' => ['title' => 'Ninoy Aquino Day',       'description' => 'National holiday'],
            '2024-08-26' => ['title' => 'National Heroes Day',    'description' => 'National holiday'],
            '2024-11-01' => ['title' => "All Saints' Day",        'description' => 'National holiday'],
            '2024-11-02' => ['title' => "All Souls' Day",         'description' => 'National holiday'],
            '2024-12-08' => ['title' => "Immaculate Conception",  'description' => 'National holiday'],
            '2024-12-25' => ['title' => 'Christmas Day',          'description' => 'National holiday'],
            '2024-12-30' => ['title' => "Rizal Day",              'description' => 'National holiday'],
            '2025-01-01' => ['title' => "New Year's Day",         'description' => 'National holiday'],
            '2025-02-25' => ['title' => 'EDSA Revolution Day',    'description' => 'National holiday'],
            '2025-04-09' => ['title' => 'Araw ng Kagitingan',     'description' => 'National holiday'],
            '2025-05-01' => ['title' => 'Labor Day',              'description' => 'National holiday'],
        ];

        $events = [
            '2024-07-15' => ['title' => 'Foundation Day',         'description' => 'School Foundation Anniversary'],
            '2024-08-05' => ['title' => 'IT Week',                'description' => 'Information Technology Week celebration'],
            '2024-09-10' => ['title' => 'Sports Fest',            'description' => 'Annual intramural sports festival'],
            '2024-10-15' => ['title' => 'Cultural Show',          'description' => 'Annual cultural night presentation'],
            '2024-11-20' => ['title' => 'Graduation Ceremony',    'description' => 'Graduation rites for graduating students'],
            '2025-01-20' => ['title' => 'Enrollment Period',      'description' => '2nd Semester enrollment'],
            '2025-02-14' => ['title' => "Valentine's Day Event",  'description' => 'Student organization event'],
            '2025-03-01' => ['title' => 'Science Fair',           'description' => 'Annual science and technology fair'],
        ];

        $schoolDays = [];
        $current = strtotime('2024-06-03');
        $end     = strtotime('2025-05-30');

        while ($current <= $end) {
            $dateStr  = date('Y-m-d', $current);
            $dayOfWeek = date('N', $current); // 1=Mon, 7=Sun

            // Skip weekends
            if ($dayOfWeek >= 6) {
                $current = strtotime('+1 day', $current);
                continue;
            }

            if (isset($holidays[$dateStr])) {
                $schoolDays[] = [
                    'date'             => $dateStr,
                    'day_type'         => 'holiday',
                    'title'            => $holidays[$dateStr]['title'],
                    'description'      => $holidays[$dateStr]['description'],
                    'attendance_rate'  => 0,
                    'students_present' => 0,
                    'students_absent'  => $totalStudents,
                    'school_year'      => '2024-2025',
                    'semester'         => $dateStr < '2025-01-06' ? '1st' : '2nd',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            } elseif (isset($events[$dateStr])) {
                $schoolDays[] = [
                    'date'             => $dateStr,
                    'day_type'         => 'event',
                    'title'            => $events[$dateStr]['title'],
                    'description'      => $events[$dateStr]['description'],
                    'attendance_rate'  => rand(85, 99),
                    'students_present' => rand(440, 515),
                    'students_absent'  => rand(5, 80),
                    'school_year'      => '2024-2025',
                    'semester'         => $dateStr < '2025-01-06' ? '1st' : '2nd',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            } else {
                // Regular class day — vary attendance slightly
                $present = rand(420, 510);
                $absent  = $totalStudents - $present;
                $rate    = round(($present / $totalStudents) * 100, 2);

                $schoolDays[] = [
                    'date'             => $dateStr,
                    'day_type'         => 'class',
                    'title'            => 'Regular Class Day',
                    'description'      => null,
                    'attendance_rate'  => $rate,
                    'students_present' => $present,
                    'students_absent'  => $absent,
                    'school_year'      => '2024-2025',
                    'semester'         => $dateStr < '2025-01-06' ? '1st' : '2nd',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }

            $current = strtotime('+1 day', $current);
        }

        foreach (array_chunk($schoolDays, 100) as $chunk) {
            \Illuminate\Support\Facades\DB::table('school_days')->insert($chunk);
        }

        $this->command->info('✅ SchoolDaySeeder: Academic calendar seeded successfully (' . count($schoolDays) . ' days).');
    }
}