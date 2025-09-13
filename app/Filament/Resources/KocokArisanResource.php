<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KocokArisans\Pages\CreateKocokArisan;
use App\Filament\Resources\KocokArisans\Pages\EditKocokArisan;
use App\Filament\Resources\KocokArisans\Pages\ListKocokArisans;
use App\Filament\Resources\KocokArisans\Pages\ViewKocokArisan;
use App\Filament\Resources\KocokArisans\Schemas\KocokArisanForm;
use App\Filament\Resources\KocokArisans\Schemas\KocokArisanInfolist;
use App\Filament\Resources\KocokArisans\Tables\KocokArisansTable;
use App\Models\KocokArisan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\NavigationGroups;
use Illuminate\Support\Facades\Auth;

class KocokArisanResource extends Resource
{
    protected static ?string $model = KocokArisan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;
    protected static string|UnitEnum|null $navigationGroup = NavigationGroups::ArisanManagement;

    protected static ?string $navigationLabel = 'Penerima Kocok Arisan';
    
    protected static ?string $recordTitleAttribute = 'Kocok Arisan';
    
    protected static ?string $pluralModelLabel = 'Kocok Arisan';
    
    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return KocokArisanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KocokArisanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KocokArisansTable::configure($table);
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
            'index' => ListKocokArisans::route('/'),
            'create' => CreateKocokArisan::route('/create'),
            'view' => ViewKocokArisan::route('/{record}'),
            'edit' => EditKocokArisan::route('/{record}/edit'),
        ];
    }
}
