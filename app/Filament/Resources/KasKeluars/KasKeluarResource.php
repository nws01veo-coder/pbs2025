<?php

namespace App\Filament\Resources\KasKeluars;

use App\Filament\Resources\KasKeluars\Pages\CreateKasKeluar;
use App\Filament\Resources\KasKeluars\Pages\EditKasKeluar;
use App\Filament\Resources\KasKeluars\Pages\ListKasKeluars;
use App\Filament\Resources\KasKeluars\Pages\ViewKasKeluar;
use App\Filament\Resources\KasKeluars\Schemas\KasKeluarForm;
use App\Filament\Resources\KasKeluars\Schemas\KasKeluarInfolist;
use App\Filament\Resources\KasKeluars\Tables\KasKeluarsTable;
use App\Filament\Resources\NavigationGroups;
use App\Models\KasKeluar;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasKeluarResource extends Resource
{
    protected static ?string $model = KasKeluar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowUpTray;

    protected static ?string $recordTitleAttribute = 'KasKeluar';
    protected static ?string $navigationLabel = 'Kas Keluar';
    protected static ?string $pluralModelLabel = 'Kas Keluar';
    protected static string | UnitEnum | null $navigationGroup = NavigationGroups::KasManagement;
    protected static ?int $navigationSort = 31;

    public static function form(Schema $schema): Schema
    {
        return KasKeluarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasKeluarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasKeluarsTable::configure($table);
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
            'index' => ListKasKeluars::route('/'),
            'create' => CreateKasKeluar::route('/create'),
            'view' => ViewKasKeluar::route('/{record}'),
            'edit' => EditKasKeluar::route('/{record}/edit'),
        ];
    }
}
