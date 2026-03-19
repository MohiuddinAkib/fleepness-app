<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\SizeTemplates\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SizeTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vendor_profile_id')
                    ->relationship('vendorProfile', 'id')
                    ->required(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
