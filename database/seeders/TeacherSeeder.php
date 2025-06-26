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
        $teachers = [
            [
                'fullname' => 'John Smith',
                'username' => 'johnsmith',
                'email' => 'john.smith@englishcenter.com',
                'date_of_birth' => '1985-07-10',
                'gender' => 1,
            ],
            [
                'fullname' => 'Emily Nguyen',
                'username' => 'gvnhut',
                'email' => 'emily.nguyen@englishcenter.com',
                'date_of_birth' => '1990-03-22',
                'gender' => 0,
            ],
            [
                'fullname' => 'Michael Johnson',
                'username' => 'mjohnson',
                'email' => 'michael.johnson@englishcenter.com',
                'date_of_birth' => '1988-11-15',
                'gender' => 1,
            ],
            [
                'fullname' => 'Sarah Wilson',
                'username' => 'swilson',
                'email' => 'sarah.wilson@englishcenter.com',
                'date_of_birth' => '1992-06-30',
                'gender' => 0,
            ],
            [
                'fullname' => 'David Brown',
                'username' => 'dbrown',
                'email' => 'david.brown@englishcenter.com',
                'date_of_birth' => '1987-04-12',
                'gender' => 1,
            ],
            [
                'fullname' => 'Lisa Anderson',
                'username' => 'landerson',
                'email' => 'lisa.anderson@englishcenter.com',
                'date_of_birth' => '1989-09-18',
                'gender' => 0,
            ],
            [
                'fullname' => 'Robert Taylor',
                'username' => 'rtaylor',
                'email' => 'robert.taylor@englishcenter.com',
                'date_of_birth' => '1986-12-05',
                'gender' => 1,
            ],
            [
                'fullname' => 'Jennifer Davis',
                'username' => 'jdavis',
                'email' => 'jennifer.davis@englishcenter.com',
                'date_of_birth' => '1991-02-14',
                'gender' => 0,
            ],
            [
                'fullname' => 'Christopher Miller',
                'username' => 'cmiller',
                'email' => 'christopher.miller@englishcenter.com',
                'date_of_birth' => '1984-08-27',
                'gender' => 1,
            ],
            [
                'fullname' => 'Amanda Garcia',
                'username' => 'agarcia',
                'email' => 'amanda.garcia@englishcenter.com',
                'date_of_birth' => '1993-05-11',
                'gender' => 0,
            ],
            [
                'fullname' => 'James Rodriguez',
                'username' => 'jrodriguez',
                'email' => 'james.rodriguez@englishcenter.com',
                'date_of_birth' => '1988-10-03',
                'gender' => 1,
            ],
            [
                'fullname' => 'Michelle Lee',
                'username' => 'mlee',
                'email' => 'michelle.lee@englishcenter.com',
                'date_of_birth' => '1990-07-20',
                'gender' => 0,
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create([
                'fullname' => $teacher['fullname'],
                'username' => $teacher['username'],
                'password' => bcrypt('123456'),
                'date_of_birth' => $teacher['date_of_birth'],
                'gender' => $teacher['gender'],
                'email' => $teacher['email'],
                'is_status' => 1
            ]);
        }
    }
}
