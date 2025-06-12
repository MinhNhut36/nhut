<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([         
                'fullname' => 'Admin',
                'username' => 'ad1',
                'password' => bcrypt('123456'), 
                'email' => 'admin@gmail.com',
            ]);
    }
}
