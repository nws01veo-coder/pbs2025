<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KocokArisanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for kocok arisan
        $permissions = [
            [
                'name' => 'kocok_arisan',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage_kocok_arisan',
                'guard_name' => 'web',
            ],
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate($permission);
        }

        // Assign permissions to administrator role
        $adminRole = \Spatie\Permission\Models\Role::where('name', 'administrator')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(['kocok_arisan', 'manage_kocok_arisan']);
        }

        // App user role tidak diberi permission kocok_arisan secara default
        // Hanya administrator yang bisa akses fitur kocok arisan

        $this->command->info('Kocok Arisan permissions created and assigned successfully!');
        $this->command->info('Administrator role: dapat kocok_arisan & manage_kocok_arisan');
        $this->command->info('App user role: tidak dapat permission kocok arisan');
    }
}
