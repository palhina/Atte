<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $users = [
        [
            'name' => 'テスト太郎',
            'email' => 'test1@mail.com',
            'password' =>Hash::make('1234567890'),
        ],
        [
            'name' => 'テスト次郎',
            'email' => 'test2@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'name' => 'テスト三郎',
            'email' => 'test3@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'name' => 'テスト四郎',
            'email' => 'test4@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'name' => 'テスト五郎',
            'email' => 'test5@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'name' => 'テスト六郎',
            'email' => 'test6@mail.com',
            'password' => Hash::make('1234567890')
        ],
    ];
    foreach ($users as $user) 
        {
        DB::table('users')->insert([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
        ]);
        }
    }
}

