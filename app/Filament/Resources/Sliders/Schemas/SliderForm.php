<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name'),
                Select::make('tag_id')
                    ->relationship('tag', 'name'),
                FileUpload::make('image_path')
                    ->image()
                    ->required(),
                TextInput::make('url')
                    ->url(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
