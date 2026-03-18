<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone_number')
                    ->tel()
                    ->required(),
                TextInput::make('password')
                    ->password(),
                TextInput::make('provider'),
                TextInput::make('provider_id'),
                DateTimePicker::make('email_verified_at'),
            ]);
    }
}
