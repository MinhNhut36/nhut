<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teacher::create(
            [         
                'fullname' => 'Nguyễn Viết Hoàng Nguyên',
                'username' => 'gvnguyen',
                'password' => bcrypt('123456'),
                'date_of_birth' => '1980-06-30',
                'gender' => 1,
                'email' => 'nguyen@gmail.com',
                'is_status' => 1
            ],
            [         
                'fullname' => 'Phạm Phú Hoàng Sơn',
                'username' => 'gvson',
                'password' => bcrypt('123456'),
                'date_of_birth' => '1990-02-20',
                'gender' => 1,
                'email' => 'son@gmail.com',
                'is_status' => 1
            ],
        );
    }
}
