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
            'id' => '1',
            'name' => 'テスト太郎',
            'email' => '111@mail.com',
            'password' =>Hash::make('1234567890'),
        ],
        [
            'id' => '2',
            'name' => 'テスト次郎',
            'email' => '222@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'id' => '3',
            'name' => 'テスト三郎',
            'email' => '333@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'id' => '4',
            'name' => 'テスト四郎',
            'email' => '444@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'id' => '5',
            'name' => 'テスト五郎',
            'email' => '555@mail.com',
            'password' => Hash::make('1234567890')
        ],
        [
            'id' => '6',
            'name' => 'テスト六郎',
            'email' => '666@mail.com',
            'password' => Hash::make('1234567890')
        ],
    ];
    foreach ($users as $user) 
        {
        DB::table('users')->insert([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
        ]);
        }
    }
}

