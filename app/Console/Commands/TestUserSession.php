<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSession;
use App\Models\User;
use Illuminate\Support\Str;

class TestUserSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-session {--count=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test user sessions for real-time dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        $users = User::limit($count)->get();
        
        if ($users->count() === 0) {
            $this->error('No users found in database');
            return;
        }
        
        $this->info("Creating {$count} test user sessions...");
        
        foreach ($users as $user) {
            UserSession::create([
                'user_id' => $user->id,
                'session_id' => 'test-' . Str::uuid(),
                'last_ping' => now(),
                'device_type' => ['android', 'ios', 'web'][rand(0, 2)],
                'app_version' => '1.0.' . rand(0, 5),
                'is_online' => true,
            ]);
            
            $this->info("Created session for user: {$user->name}");
        }
        
        $totalOnline = UserSession::where('is_online', true)->count();
        $this->info("Total online users: {$totalOnline}");
        
        return 0;
    }
}
