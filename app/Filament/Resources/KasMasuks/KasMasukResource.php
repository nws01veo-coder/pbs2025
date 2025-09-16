<?php

namespace App\Filament\Resources\KasMasuks;

use App\Filament\Resources\KasMasuks\Pages\CreateKasMasuk;
use App\Filament\Resources\KasMasuks\Pages\EditKasMasuk;
use App\Filament\Resources\KasMasuks\Pages\ListKasMasuks;
use App\Filament\Resources\KasMasuks\Pages\ViewKasMasuk;
use App\Filament\Resources\KasMasuks\Schemas\KasMasukForm;
use App\Filament\Resources\KasMasuks\Schemas\KasMasukInfolist;
use App\Filament\Resources\KasMasuks\Tables\KasMasuksTable;
use App\Filament\Resources\NavigationGroups;
use App\Models\KasMasuk;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasMasukResource extends Resource
{
    protected static ?string $model = KasMasuk::class;
    protected static ?string $slug = 'kas-masuk';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowDownTray;
    protected static string | UnitEnum | null $navigationGroup = NavigationGroups::KasManagement;
    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'KasMasuk';
    protected static ?string $navigationLabel = 'Kas Masuk';
    protected static ?string $pluralModelLabel = 'Kas Masuk';

    public static function form(Schema $schema): Schema
    {
        return KasMasukForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasMasukInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasMasuksTable::configure($table);
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
            'index' => ListKasMasuks::route('/'),
            'create' => CreateKasMasuk::route('/create'),
            'view' => ViewKasMasuk::route('/{record}'),
            'edit' => EditKasMasuk::route('/{record}/edit'),
        ];
    }
}
