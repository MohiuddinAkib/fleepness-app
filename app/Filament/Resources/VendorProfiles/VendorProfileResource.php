<?php

namespace App\Filament\Resources\VendorProfiles;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\VendorProfile;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\VendorProfiles\Pages\EditVendorProfile;
use App\Filament\Resources\VendorProfiles\Pages\ListVendorProfiles;
use App\Filament\Resources\VendorProfiles\Pages\CreateVendorProfile;
use App\Filament\Resources\VendorProfiles\Schemas\VendorProfileForm;
use App\Filament\Resources\VendorProfiles\Tables\VendorProfilesTable;

class VendorProfileResource extends Resource
{
    protected static ?string $model = VendorProfile::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Vendors';

    protected static null|string|\UnitEnum $navigationGroup = 'Users';

    protected static ?string $recordTitleAttribute = 'shop_name';

    public static function form(Schema $schema): Schema
    {
        return VendorProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorProfilesTable::configure($table);
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
            'index' => ListVendorProfiles::route('/'),
            'create' => CreateVendorProfile::route('/create'),
            'edit' => EditVendorProfile::route('/{record}/edit'),
        ];
    }
}
