<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Tables\Table;
use App\Enums\ProductStatus;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use App\Enums\ProductApprovalStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use App\Actions\Admin\ApproveProductAction;
use Filament\Actions\ForceDeleteBulkAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vendorProfile.shop_name')
                    ->label('Vendor')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('selling_price')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->icon(fn (ProductStatus $state): string => $state->getIcon())
                    ->color(fn (ProductStatus $state): string => $state->getColor())
                    ->sortable(),
                TextColumn::make('is_approved')
                    ->label('Approval')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => ProductApprovalStatus::fromBoolean($state)->getLabel())
                    ->icon(fn (bool $state): string => ProductApprovalStatus::fromBoolean($state)->getIcon())
                    ->color(fn (bool $state): string => ProductApprovalStatus::fromBoolean($state)->getColor()),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProductStatus::class),
                SelectFilter::make('is_approved')
                    ->label('Approval')
                    ->options(ProductApprovalStatus::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color(Color::Green)
                    ->requiresConfirmation()
                    ->visible(fn (Product $record): bool => ! $record->is_approved)
                    ->action(fn (Product $record, ApproveProductAction $action) => $action->execute($record, true)),
                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color(Color::Red)
                    ->requiresConfirmation()
                    ->visible(fn (Product $record): bool => (bool) $record->is_approved)
                    ->action(fn (Product $record, ApproveProductAction $action) => $action->execute($record, false)),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
