<?php

namespace App\Filament\Resources\JadwalArisans;

use App\Filament\Resources\JadwalArisans\Pages\CreateJadwalArisan;
use App\Filament\Resources\JadwalArisans\Pages\EditJadwalArisan;
use App\Filament\Resources\JadwalArisans\Pages\ListJadwalArisans;
use App\Filament\Resources\JadwalArisans\Pages\ViewJadwalArisan;
use App\Filament\Resources\JadwalArisans\Schemas\JadwalArisanForm;
use App\Filament\Resources\JadwalArisans\Schemas\JadwalArisanInfolist;
use App\Filament\Resources\JadwalArisans\Tables\JadwalArisansTable;
use App\Filament\Resources\NavigationGroups;
use App\Models\JadwalArisan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JadwalArisanResource extends Resource
{
    protected static ?string $model = JadwalArisan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static string|UnitEnum|null $navigationGroup = NavigationGroups::ArisanManagement;
    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'JadwalArisan';
    protected static ?string $slug = 'jadwal';
    protected static ?string $navigationLabel = 'Jadwal Arisan';
    protected static ?string $pluralModelLabel = 'Jadwal Arisan';

    public static function form(Schema $schema): Schema
    {
        return JadwalArisanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JadwalArisanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalArisansTable::configure($table);
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
            'index' => ListJadwalArisans::route('/'),
            'create' => CreateJadwalArisan::route('/create'),
            'view' => ViewJadwalArisan::route('/{record}'),
            'edit' => EditJadwalArisan::route('/{record}/edit'),
        ];
    }
}
