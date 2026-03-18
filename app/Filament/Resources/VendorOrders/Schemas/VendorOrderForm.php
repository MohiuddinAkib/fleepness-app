<?php

namespace App\Filament\Resources\VendorOrders\Schemas;

use Filament\Schemas\Schema;
use App\Enums\VendorOrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class VendorOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Select::make('vendor_profile_id')
                    ->relationship('vendorProfile', 'id')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                TextInput::make('order_number')
                    ->required(),
                Select::make('status')
                    ->options(VendorOrderStatus::class)
                    ->default('pending')
                    ->required(),
                Textarea::make('status_note')
                    ->columnSpanFull(),
                TextInput::make('product_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('commission')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('vat')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivery_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('is_rider_assigned')
                    ->required(),
                Toggle::make('is_delayed')
                    ->required(),
                DateTimePicker::make('packaging_started_at'),
                DateTimePicker::make('expected_delivery_at'),
            ]);
    }
}
