<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance; 

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = [
            [
                'user_id' => 1,
                'start_time' => '2021-11-01 10:00:00',
                'end_time' => '2021-11-01 20:00:00',
                'year' => '2021',
                'month' => '11',
                'day' => '01'
            ],
            [
                'user_id' => 2,
                'start_time' => '2021-11-01 10:00:10',
                'end_time' => '2021-11-01 20:00:00',
                'year' => '2021',
                'month' => '11',
                'day' => '01'
            ],
            [
                'user_id' => 3,
                'start_time' => '2021-11-01 10:00:10',
                'end_time' => '2021-11-01 20:00:00',
                'year' => '2021',
                'month' => '11',
                'day' => '01'
            ],
            [
                'user_id' => 4,
                'start_time' => '2021-11-01 10:00:20',
                'end_time' => '2021-11-01 20:00:00',
                'year' => '2021',
                'month' => '11',
                'day' => '01'
            ],
            [
                'user_id' => 5,
                'start_time' => '2021-11-01 10:00:20',
                'end_time' => '2021-11-01 20:00:00',
                'year' => '2021',
                'month' => '11',
                'day' => '01'
            ],
            [
                'user_id' => 6,
                'start_time' => '2023-08-30 08:00:20',
                'end_time' => '2023-08-30 20:00:00',
                'year' => '2023',
                'month' => '08',
                'day' => '30'
            ]
        ];
        foreach ($attendances as $attendance) 
        {
            Attendance::create([
            'user_id' => $attendance['user_id'],
            'start_time' => $attendance['start_time'],
            'end_time' => $attendance['end_time'],
            'year' => $attendance['year'],
            'month' => $attendance['month'],
            'day' => $attendance['day'],
        ]);
        }
    }
}
