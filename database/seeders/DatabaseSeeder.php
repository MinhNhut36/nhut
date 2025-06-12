<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\LessonPartContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
   
          $this->call([
            AdminSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            LessonSeeder::class,
            CourseSeeder::class,
            LessonPartSeeder::class,
            LessonPartContenttSeeder::class,
        ]);
                      
    }
}
