<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\ShortVideos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ShortVideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vendor_profile_id')
                    ->relationship('vendorProfile', 'id')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('video_path')
                    ->required(),
                TextInput::make('thumbnail_path'),
                TextInput::make('likes_count')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
