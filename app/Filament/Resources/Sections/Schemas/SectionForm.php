<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sections\Schemas;

use App\Enums\SectionType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('title'),
                Select::make('type')
                    ->options(SectionType::class)
                    ->default('scrollable_product')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('placement_type'),
                FileUpload::make('background_image_path')
                    ->image(),
                FileUpload::make('banner_image_path')
                    ->image(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('category_sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_visible')
                    ->required(),
            ]);
    }
}
