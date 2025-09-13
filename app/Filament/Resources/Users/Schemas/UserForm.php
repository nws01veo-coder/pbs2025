<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('anggota_id')
                    ->relationship('anggota', 'name')
                    ->label('Name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit'),
                CheckboxList::make('roles')
                    ->label('User Access/Roles')
                    ->options(function() {
                        return Role::all()->pluck('name', 'name')->toArray();
                    })
                    ->required()
                    ->columns(2),
            ]);
    }
}
