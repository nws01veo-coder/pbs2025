<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        // Dashboard permissions
        Permission::create(['name' => 'access_dashboard']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_anggota']);
        Permission::create(['name' => 'manage_kas']);
        Permission::create(['name' => 'manage_jadwal']);
        Permission::create(['name' => 'manage_gallery']);
        Permission::create(['name' => 'manage_kocok_arisan']);
        
        // App permissions
        Permission::create(['name' => 'access_app']);
        Permission::create(['name' => 'view_anggota']);
        Permission::create(['name' => 'view_kas']);
        Permission::create(['name' => 'view_jadwal']);
        Permission::create(['name' => 'view_gallery']);
        Permission::create(['name' => 'edit_profile']);
        Permission::create(['name' => 'kocok_arisan']);

        // Create roles and assign permissions
        // Administrator role (full access)
        $adminRole = Role::create(['name' => 'administrator']);
        $adminRole->givePermissionTo(Permission::all());

        // App User role (only access to the Flutter app)
        $appUserRole = Role::create(['name' => 'app_user']);
        $appUserRole->givePermissionTo([
            'access_app',
            'view_anggota',
            'view_kas',
            'view_jadwal',
            'view_gallery',
            'edit_profile',
        ]);

        // Assign admin role to existing users with email containing admin
        User::where('email', 'like', '%admin%')->get()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
        });

        // Assign app_user role to other users
        User::whereNotIn('id', User::role('administrator')->pluck('id'))->get()->each(function ($user) use ($appUserRole) {
            $user->assignRole($appUserRole);
        });
    }
}
