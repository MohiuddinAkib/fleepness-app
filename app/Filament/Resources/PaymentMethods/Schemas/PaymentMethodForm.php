<?php

declare(strict_types=1);

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('icon_path'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
