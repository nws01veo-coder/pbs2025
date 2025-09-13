<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'role:assign-admin {email}';
    protected $description = 'Assigns the administrator role to a user with the given email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found");
            return 1;
        }
        
        $adminRole = Role::where('name', 'administrator')->first();
        
        if (!$adminRole) {
            $this->error("Administrator role not found");
            return 1;
        }
        
        $user->assignRole($adminRole);
        
        $this->info("Administrator role assigned to {$user->name} ({$email})");
        
        return 0;
    }
}
