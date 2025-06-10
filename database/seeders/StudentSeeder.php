<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create(
        [
            [
                'avatar' => 'avatar1.png',
                'fullname' => 'Dương Minh Nhựt',
                'username' => 'nhut',
                'password' => bcrypt('123456'),
                'date_of_birth' => '2002-05-10',
                'gender' => 1,
                'email' => 'nhut@gmail.com',
                'is_status' => 1  
            ],
            [
                'avatar' => 'avatar1.png',
                'fullname' => 'Nguyễn Huyền Trang',
                'username' => 'atrang',
                'password' => bcrypt('123456'),
                'date_of_birth' => '2002-02-10',
                'gender' => 0,
                'email' => 'nhut@gmail.com',
                'is_status' => 1  
            ]
        ]
        );
    }
}
