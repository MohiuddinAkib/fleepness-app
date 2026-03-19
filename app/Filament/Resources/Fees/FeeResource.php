<?php

declare(strict_types=1);

namespace App\Filament\Resources\Fees;

use BackedEnum;
use App\Models\Fee;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Fees\Pages\ManageFees;

class FeeResource extends Resource
{
    protected static ?string $model = Fee::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static null|string|\UnitEnum $navigationGroup = 'Finance';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vat')
                    ->required()
                    ->numeric(),
                TextInput::make('platform_fee')
                    ->required()
                    ->numeric(),
                TextInput::make('commission')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('platform_fee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('commission')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFees::route('/'),
        ];
    }
}
