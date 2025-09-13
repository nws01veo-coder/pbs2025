<?php

namespace App\Filament\Resources\Anggotas;

use App\Filament\Resources\Anggotas\Pages\CreateAnggota;
use App\Filament\Resources\Anggotas\Pages\EditAnggota;
use App\Filament\Resources\Anggotas\Pages\ListAnggotas;
use App\Filament\Resources\Anggotas\Pages\ViewAnggota;
use App\Filament\Resources\Anggotas\Schemas\AnggotaForm;
use App\Filament\Resources\Anggotas\Schemas\AnggotaInfolist;
use App\Filament\Resources\Anggotas\Tables\AnggotasTable;
use App\Models\Anggota;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\NavigationGroups;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string | \UnitEnum | null $navigationGroup = NavigationGroups::AnggotaManagement;
    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'Anggota';
    protected static ?string $navigationLabel = 'Data Anggota';
    protected static ?string $pluralModelLabel = 'Anggota';
    

    public static function form(Schema $schema): Schema
    {
        return AnggotaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AnggotaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnggotasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnggotas::route('/'),
            'create' => CreateAnggota::route('/create'),
            'view' => ViewAnggota::route('/{record}'),
            'edit' => EditAnggota::route('/{record}/edit'),
        ];
    }
}
