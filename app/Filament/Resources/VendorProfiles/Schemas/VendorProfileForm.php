<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorProfiles\Schemas;

use App\Enums\VendorStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class VendorProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('shop_category_id')
                    ->relationship('shopCategory', 'name')
                    ->searchable(),
                TextInput::make('shop_name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('pickup_location'),
                Select::make('status')
                    ->options(VendorStatus::class)
                    ->default(VendorStatus::Pending->value)
                    ->required(),
                Textarea::make('status_note')
                    ->columnSpanFull(),
            ]);
    }
}
