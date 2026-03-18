<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('delivery_option_id')
                    ->relationship('deliveryOption', 'name'),
                TextInput::make('order_number')
                    ->required(),
                Toggle::make('is_multi_vendor')
                    ->required(),
                TextInput::make('vendor_count')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('product_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivery_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('platform_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('vat')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('commission')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('grand_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('is_completed')
                    ->required(),
            ]);
    }
}
