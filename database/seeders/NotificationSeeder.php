<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert(
        [
            [
                'user_id' => 1,
                'target' => '',
                'title' => '',
                'message' => '',
                'notification_date' => now(),
                'status' => 1,
            ],
            [
                'user_id' => 1,
                'target' => '',
                'title' => '',
                'message' => '',
                'notification_date' => now(),
                'status' => 1,
            ],
        ]
        );
    }
}
