<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\SizeTemplates;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\SizeTemplate;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Vendor\Resources\SizeTemplates\Pages\EditSizeTemplate;
use App\Filament\Vendor\Resources\SizeTemplates\Pages\ListSizeTemplates;
use App\Filament\Vendor\Resources\SizeTemplates\Pages\CreateSizeTemplate;
use App\Filament\Vendor\Resources\SizeTemplates\Schemas\SizeTemplateForm;
use App\Filament\Vendor\Resources\SizeTemplates\Tables\SizeTemplatesTable;

class SizeTemplateResource extends Resource
{
    protected static ?string $model = SizeTemplate::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SizeTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SizeTemplatesTable::configure($table);
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
            'index' => ListSizeTemplates::route('/'),
            'create' => CreateSizeTemplate::route('/create'),
            'edit' => EditSizeTemplate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $vendorProfileId = auth()->user()?->vendorProfile?->getKey();

        return parent::getEloquentQuery()
            ->where('vendor_profile_id', $vendorProfileId);
    }
}
