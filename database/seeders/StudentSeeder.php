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
        $students = [
            [
                'avatar' => 'avatar1.png',
                'fullname' => 'Nguyễn Thị Huyền Trang',
                'username' => 'trang',
                'email' => '0306221491@caothang.edu.vn',
                'date_of_birth' => '2002-05-10',
                'gender' => 0,
            ],
            [
                'avatar' => 'avatar2.png',
                'fullname' => 'Dương Minh Nhựt',
                'username' => 'nhut',
                'email' => '0306221455@caothang.edu.vn',
                'date_of_birth' => '2002-09-21',
                'gender' => 1,
            ],
            [
                'avatar' => 'avatar3.png',
                'fullname' => 'Trần Văn An',
                'username' => 'vanan',
                'email' => 'vanan@caothang.edu.vn',
                'date_of_birth' => '2001-12-15',
                'gender' => 1,
            ],
            [
                'avatar' => 'avatar4.png',
                'fullname' => 'Lê Thị Mai',
                'username' => 'thimai',
                'email' => 'thimai@caothang.edu.vn',
                'date_of_birth' => '2003-03-08',
                'gender' => 0,
            ],
            [
                'avatar' => 'avatar5.png',
                'fullname' => 'Phạm Hoàng Long',
                'username' => 'hoanglong',
                'email' => 'hoanglong@caothang.edu.vn',
                'date_of_birth' => '2002-07-22',
                'gender' => 1,
            ],
            [
                'avatar' => 'avatar6.png',
                'fullname' => 'Võ Thị Bích',
                'username' => 'thibich',
                'email' => 'thibich@caothang.edu.vn',
                'date_of_birth' => '2001-11-03',
                'gender' => 0,
            ],
            [
                'avatar' => 'avatar7.png',
                'fullname' => 'Nguyễn Văn Cường',
                'username' => 'vancuong',
                'email' => 'vancuong@caothang.edu.vn',
                'date_of_birth' => '2000-08-17',
                'gender' => 1,
            ],
            [
                'avatar' => 'avatar8.png',
                'fullname' => 'Trần Thị Dung',
                'username' => 'thidung',
                'email' => 'thidung@caothang.edu.vn',
                'date_of_birth' => '2002-04-25',
                'gender' => 0,
            ],
            [
                'avatar' => 'avatar9.png',
                'fullname' => 'Lý Văn Em',
                'username' => 'vanem',
                'email' => 'vanem@caothang.edu.vn',
                'date_of_birth' => '2001-06-12',
                'gender' => 1,
            ],
            [
                'avatar' => 'avatar10.png',
                'fullname' => 'Phan Thị Phương',
                'username' => 'thiphuong',
                'email' => 'thiphuong@caothang.edu.vn',
                'date_of_birth' => '2003-01-30',
                'gender' => 0,
            ],
            [
                'avatar' => 'avatar11.png',
                'fullname' => 'Đặng Văn Giang',
                'username' => 'vangiang',
                'email' => 'vangiang@caothang.edu.vn',
                'date_of_birth' => '2000-10-08',
                'gender' => 1,
            ],
            [
                'avatar' => 'avatar12.png',
                'fullname' => 'Bùi Thị Hoa',
                'username' => 'thihoa',
                'email' => 'thihoa@caothang.edu.vn',
                'date_of_birth' => '2002-12-14',
                'gender' => 0,
            ],
        ];

        foreach ($students as $student) {
            Student::create([
                'avatar' => $student['avatar'],
                'fullname' => $student['fullname'],
                'username' => $student['username'],
                'password' => bcrypt('123456'),
                'date_of_birth' => $student['date_of_birth'],
                'gender' => $student['gender'],
                'email' => $student['email'],
                'is_status' => 1
            ]);
        }
    }
}
