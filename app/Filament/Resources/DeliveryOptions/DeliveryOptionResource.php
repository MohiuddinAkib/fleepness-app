<?php

declare(strict_types=1);

namespace App\Filament\Resources\DeliveryOptions;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\DeliveryOption;
use App\Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\DeliveryOptions\Pages\EditDeliveryOption;
use App\Filament\Resources\DeliveryOptions\Pages\ListDeliveryOptions;
use App\Filament\Resources\DeliveryOptions\Pages\CreateDeliveryOption;
use App\Filament\Resources\DeliveryOptions\Schemas\DeliveryOptionForm;
use App\Filament\Resources\DeliveryOptions\Tables\DeliveryOptionsTable;

class DeliveryOptionResource extends Resource
{
    protected static ?string $model = DeliveryOption::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static null|string|\UnitEnum $navigationGroup = 'Commerce';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DeliveryOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryOptionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeliveryOptions::route('/'),
            'create' => CreateDeliveryOption::route('/create'),
            'edit' => EditDeliveryOption::route('/{record}/edit'),
        ];
    }
}
