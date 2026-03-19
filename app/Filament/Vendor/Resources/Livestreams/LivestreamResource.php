<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\Livestreams;

use BackedEnum;
use App\Models\Livestream;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Vendor\Resources\Livestreams\Pages\EditLivestream;
use App\Filament\Vendor\Resources\Livestreams\Pages\ListLivestreams;
use App\Filament\Vendor\Resources\Livestreams\Pages\CreateLivestream;
use App\Filament\Vendor\Resources\Livestreams\Schemas\LivestreamForm;
use App\Filament\Vendor\Resources\Livestreams\Tables\LivestreamsTable;

class LivestreamResource extends Resource
{
    protected static ?string $model = Livestream::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LivestreamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LivestreamsTable::configure($table);
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
            'index' => ListLivestreams::route('/'),
            'create' => CreateLivestream::route('/create'),
            'edit' => EditLivestream::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $vendorProfileId = auth()->user()?->vendorProfile?->getKey();

        return parent::getEloquentQuery()
            ->where('vendor_profile_id', $vendorProfileId);
    }
}
