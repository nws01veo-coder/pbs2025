<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserActivitySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please create users first.');
            return;
        }

        // Generate data aktivitas untuk 30 hari terakhir
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            // Untuk setiap user, generate aktivitas random
            foreach ($users as $user) {
                // Random jumlah aktivitas per hari (0-10)
                $activitiesPerDay = rand(0, 10);
                
                for ($j = 0; $j < $activitiesPerDay; $j++) {
                    // Random jam dalam sehari
                    $hour = rand(6, 23); // Aktivitas antara jam 6 pagi - 11 malam
                    $minute = rand(0, 59);
                    
                    $activityTime = $date->copy()->setHour($hour)->setMinute($minute);
                    
                    UserActivity::create([
                        'user_id' => $user->id,
                        'activity_type' => 'app_open',
                        'activity_time' => $activityTime,
                        'platform' => rand(1, 3) == 1 ? 'android' : (rand(1, 2) == 1 ? 'ios' : 'web'),
                    ]);
                }
            }
        }
        
        $this->command->info('User activities seeded successfully!');
    }
}
