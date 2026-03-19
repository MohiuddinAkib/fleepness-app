<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\VendorOrders;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\VendorOrder;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Vendor\Resources\VendorOrders\Pages\EditVendorOrder;
use App\Filament\Vendor\Resources\VendorOrders\Pages\ListVendorOrders;
use App\Filament\Vendor\Resources\VendorOrders\Pages\CreateVendorOrder;
use App\Filament\Vendor\Resources\VendorOrders\Schemas\VendorOrderForm;
use App\Filament\Vendor\Resources\VendorOrders\Tables\VendorOrdersTable;

class VendorOrderResource extends Resource
{
    protected static ?string $model = VendorOrder::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VendorOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorOrdersTable::configure($table);
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
            'index' => ListVendorOrders::route('/'),
            'create' => CreateVendorOrder::route('/create'),
            'edit' => EditVendorOrder::route('/{record}/edit'),
        ];
    }
}
