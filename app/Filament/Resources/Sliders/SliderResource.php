<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sliders;

use BackedEnum;
use App\Models\Slider;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Sliders\Pages\EditSlider;
use App\Filament\Resources\Sliders\Pages\ListSliders;
use App\Filament\Resources\Sliders\Pages\CreateSlider;
use App\Filament\Resources\Sliders\Schemas\SliderForm;
use App\Filament\Resources\Sliders\Tables\SlidersTable;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static null|string|\UnitEnum $navigationGroup = 'CMS';

    protected static ?string $recordTitleAttribute = 'url';

    public static function form(Schema $schema): Schema
    {
        return SliderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlidersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSliders::route('/'),
            'create' => CreateSlider::route('/create'),
            'edit' => EditSlider::route('/{record}/edit'),
        ];
    }
}
