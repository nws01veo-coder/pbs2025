<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->count() === 0) {
            $this->command->info('No users found. Please seed users first.');
            return;
        }

        // Clear existing sessions
        UserSession::truncate();

        $this->command->info('Creating user sessions...');

        // Create sessions untuk simulasi real-time activity
        foreach ($users as $user) {
            // Session hari ini - beberapa user online sekarang
            if (rand(1, 100) <= 30) { // 30% chance user online sekarang
                UserSession::create([
                    'user_id' => $user->id,
                    'session_id' => Str::uuid(),
                    'last_ping' => now()->subMinutes(rand(0, 4)), // 0-4 menit yang lalu
                    'device_type' => ['android', 'ios', 'web'][rand(0, 2)],
                    'app_version' => '1.0.' . rand(0, 5),
                    'is_online' => true,
                ]);
            }

            // Session beberapa jam lalu hari ini
            for ($i = 0; $i < rand(1, 4); $i++) {
                UserSession::create([
                    'user_id' => $user->id,
                    'session_id' => Str::uuid(),
                    'last_ping' => today()->addHours(rand(6, 23))->addMinutes(rand(0, 59)),
                    'device_type' => ['android', 'ios', 'web'][rand(0, 2)],
                    'app_version' => '1.0.' . rand(0, 5),
                    'is_online' => false,
                ]);
            }

            // Session minggu ini (7 hari terakhir)
            for ($day = 1; $day <= 6; $day++) {
                if (rand(1, 100) <= 60) { // 60% chance aktif per hari
                    for ($session = 0; $session < rand(1, 3); $session++) {
                        UserSession::create([
                            'user_id' => $user->id,
                            'session_id' => Str::uuid(),
                            'last_ping' => now()->subDays($day)->addHours(rand(6, 22))->addMinutes(rand(0, 59)),
                            'device_type' => ['android', 'ios', 'web'][rand(0, 2)],
                            'app_version' => '1.0.' . rand(0, 5),
                            'is_online' => false,
                        ]);
                    }
                }
            }
        }

        $totalSessions = UserSession::count();
        $onlineNow = UserSession::where('is_online', true)->count();
        
        $this->command->info("Created {$totalSessions} user sessions");
        $this->command->info("Currently online: {$onlineNow} users");
    }
}
