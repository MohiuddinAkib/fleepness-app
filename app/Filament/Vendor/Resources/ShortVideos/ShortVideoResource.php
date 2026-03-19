<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\ShortVideos;

use BackedEnum;
use App\Models\ShortVideo;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Vendor\Resources\ShortVideos\Pages\EditShortVideo;
use App\Filament\Vendor\Resources\ShortVideos\Pages\ListShortVideos;
use App\Filament\Vendor\Resources\ShortVideos\Pages\CreateShortVideo;
use App\Filament\Vendor\Resources\ShortVideos\Schemas\ShortVideoForm;
use App\Filament\Vendor\Resources\ShortVideos\Tables\ShortVideosTable;

class ShortVideoResource extends Resource
{
    protected static ?string $model = ShortVideo::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ShortVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShortVideosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShortVideos::route('/'),
            'create' => CreateShortVideo::route('/create'),
            'edit' => EditShortVideo::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $vendorProfileId = auth()->user()?->vendorProfile?->getKey();

        return parent::getEloquentQuery()
            ->where('vendor_profile_id', $vendorProfileId);
    }
}
