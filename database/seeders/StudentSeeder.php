<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create([
            'avatar' => 'avatar1.png',
            'fullname' => 'Nguyễn Thị Huyền Trang',
            'username' => 'trang',
            'password' => bcrypt('123456'),
            'date_of_birth' => '2002-05-10',
            'gender' => 0,
            'email' => '0306221491@caothang.edu.vn',
            'is_status' => 1
        ]);
        Student::create([
            'avatar' => 'avatar2.png',
            'fullname' => 'Dương Minh nhựt',
            'username' => 'nhut',
            'password' => bcrypt('123456'),
            'date_of_birth' => '2002-09-21',
            'gender' => 1,
            'email' => '0306221455@caothang.edu.vn',
            'is_status' => 1
        ]);

        Student::create([
            'avatar' => 'avatar3.png',
            'fullname' => 'Trần Văn An',
            'username' => 'vanan',
            'password' => bcrypt('123456'),
            'date_of_birth' => '2001-12-15',
            'gender' => 1,
            'email' => 'vanan@caothang.edu.vn',
            'is_status' => 1
        ]);

        Student::create([
            'avatar' => 'avatar4.png',
            'fullname' => 'Lê Thị Mai',
            'username' => 'thimai',
            'password' => bcrypt('123456'),
            'date_of_birth' => '2003-03-08',
            'gender' => 0,
            'email' => 'thimai@caothang.edu.vn',
            'is_status' => 1
        ]);

        Student::create([
            'avatar' => 'avatar5.png',
            'fullname' => 'Phạm Hoàng Long',
            'username' => 'hoanglong',
            'password' => bcrypt('123456'),
            'date_of_birth' => '2002-07-22',
            'gender' => 1,
            'email' => 'hoanglong@caothang.edu.vn',
            'is_status' => 1
        ]);
    }
}
