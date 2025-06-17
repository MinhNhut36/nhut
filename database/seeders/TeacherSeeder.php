<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teacher::create([
            'fullname' => 'John Smith',
            'username' => 'johnsmith',
            'password' => bcrypt('123456'),
            'date_of_birth' => '1985-07-10',
            'gender' => 1,
            'email' => 'john.smith@englishcenter.com',
            'is_status' => 1
        ]);
        Teacher::create([
            'fullname' => 'Emily nhut',
            'username' => 'gvnhut',
            'password' => bcrypt('123456'),
            'date_of_birth' => '1990-03-22',
            'gender' => 0,
            'email' => 'emily.nguyen@englishcenter.com',
            'is_status' => 1
        ]);

        Teacher::create([
            'fullname' => 'Michael Johnson',
            'username' => 'mjohnson',
            'password' => bcrypt('123456'),
            'date_of_birth' => '1988-11-15',
            'gender' => 1,
            'email' => 'michael.johnson@englishcenter.com',
            'is_status' => 1
        ]);

        Teacher::create([
            'fullname' => 'Sarah Wilson',
            'username' => 'swilson',
            'password' => bcrypt('123456'),
            'date_of_birth' => '1992-06-30',
            'gender' => 0,
            'email' => 'sarah.wilson@englishcenter.com',
            'is_status' => 1
        ]);

        Teacher::create([
            'fullname' => 'David Brown',
            'username' => 'dbrown',
            'password' => bcrypt('123456'),
            'date_of_birth' => '1987-04-12',
            'gender' => 1,
            'email' => 'david.brown@englishcenter.com',
            'is_status' => 1
        ]);
    }
}
