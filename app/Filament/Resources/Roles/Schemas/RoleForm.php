<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                
                CheckboxList::make('permissions')
                    ->label('Permissions')
                    ->relationship('permissions', 'name')
                    ->options(
                        Permission::all()->pluck('name', 'id')
                    )
                    ->columns(2)
                    ->descriptions([
                        'access_dashboard' => 'Akses ke dashboard admin',
                        'manage_users' => 'Mengelola user',
                        'manage_anggota' => 'Mengelola anggota',
                        'manage_kas' => 'Mengelola kas',
                        'manage_jadwal' => 'Mengelola jadwal',
                        'manage_gallery' => 'Mengelola galeri',
                        'access_app' => 'Akses ke aplikasi',
                        'view_anggota' => 'Melihat anggota',
                        'view_kas' => 'Melihat kas',
                        'view_jadwal' => 'Melihat jadwal',
                        'view_gallery' => 'Melihat galeri',
                        'edit_profile' => 'Edit profil',
                    ]),
            ]);
    }
}
