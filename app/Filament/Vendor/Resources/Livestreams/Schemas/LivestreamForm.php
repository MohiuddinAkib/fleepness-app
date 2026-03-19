<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\Livestreams\Schemas;

use Filament\Schemas\Schema;
use App\Enums\LivestreamStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class LivestreamForm
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
                TextInput::make('room_id')
                    ->required(),
                TextInput::make('egress_id'),
                TextInput::make('egress_metadata'),
                Select::make('status')
                    ->options(LivestreamStatus::class)
                    ->default('scheduled')
                    ->required(),
                TextInput::make('viewer_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('scheduled_at'),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('ended_at'),
                TextInput::make('total_duration')
                    ->numeric(),
            ]);
    }
}
