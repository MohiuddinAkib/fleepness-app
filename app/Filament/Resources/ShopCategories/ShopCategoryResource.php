<?php

declare(strict_types=1);

namespace App\Filament\Resources\ShopCategories;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\ShopCategory;
use Filament\Schemas\Schema;
use App\Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\ShopCategories\Pages\EditShopCategory;
use App\Filament\Resources\ShopCategories\Pages\CreateShopCategory;
use App\Filament\Resources\ShopCategories\Pages\ListShopCategories;
use App\Filament\Resources\ShopCategories\Schemas\ShopCategoryForm;
use App\Filament\Resources\ShopCategories\Tables\ShopCategoriesTable;

class ShopCategoryResource extends Resource
{
    protected static ?string $model = ShopCategory::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static null|string|\UnitEnum $navigationGroup = 'Catalog';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ShopCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShopCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShopCategories::route('/'),
            'create' => CreateShopCategory::route('/create'),
            'edit' => EditShopCategory::route('/{record}/edit'),
        ];
    }
}
