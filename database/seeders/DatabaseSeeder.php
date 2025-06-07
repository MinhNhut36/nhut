<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('students')->insert([         
                'avatar' => 'avatar1.png',
                'fullname' => 'Nguyễn Văn A',
                'username' => 'nhut',
                'password' => bcrypt('123456'),
                'date_of_birth' => '2002-05-10',
                'gender' => 1,
                'email' => 'vana@example.com',
                'is_status' => 1
            ]);
        
    }
}
