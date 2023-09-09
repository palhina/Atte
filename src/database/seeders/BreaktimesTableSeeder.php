<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breaktime; 

class BreaktimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $breaktimes = [
            [
                'attendance_id' => 1,
                'breakin_time' => '2021-11-01 12:00:00',
                'breakout_time' => '2021-11-01 12:30:00',
                'workbreak_seconds' => '1800'
            ],
            [
                'attendance_id' => 2,
                'breakin_time' => '2021-11-01 12:00:00',
                'breakout_time' => '2021-11-01 12:30:10',
                'workbreak_seconds' => '1790'
            ],
            [
                'attendance_id' => 3,
                'breakin_time' => '2021-11-01 12:00:00',
                'breakout_time' => '2021-11-01 12:30:10',
                'workbreak_seconds' => '1790'
            ],
            [
                'attendance_id' => 4,
                'breakin_time' => '2021-11-01 12:00:00',
                'breakout_time' => '2021-11-01 12:30:20',
                'workbreak_seconds' => '1780'
            ],
            [
                'attendance_id' => 5,
                'breakin_time' => '2021-11-01 12:00:00',
                'breakout_time' => '2021-11-01 12:30:20',
                'workbreak_seconds' => '1780'
            ],
            [
                'attendance_id' => 6,
                'breakin_time' => '2021-11-01 12:00:00',
                'breakout_time' => '2021-11-01 12:10:00',
                'workbreak_seconds' => '600'
            ]
        ];
        foreach ($breaktimes as $breaktime) 
        {
        Breaktime::create([
            'attendance_id' => $breaktime['attendance_id'],
            'breakin_time' => $breaktime['breakin_time'],
            'breakout_time' => $breaktime['breakout_time'],
            'workbreak_seconds' => $breaktime['workbreak_seconds']
        ]);
        }
    }
}
