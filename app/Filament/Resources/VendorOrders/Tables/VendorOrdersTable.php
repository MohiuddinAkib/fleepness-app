<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorOrders\Tables;

use Filament\Tables\Table;
use App\Models\VendorOrder;
use Filament\Actions\Action;
use App\Enums\VendorOrderStatus;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Notifications\VendorOrderStatusChanged;

class VendorOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('vendorProfile.shop_name')
                    ->label('Vendor')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (VendorOrderStatus $state): string => match ($state) {
                        VendorOrderStatus::Pending => 'warning',
                        VendorOrderStatus::Packaging => 'info',
                        VendorOrderStatus::OnTheWay => 'primary',
                        VendorOrderStatus::Delivered => 'success',
                        VendorOrderStatus::Rejected => 'danger',
                        VendorOrderStatus::Delayed => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('product_total')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('balance')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(VendorOrderStatus::class),
            ])
            ->recordActions([
                Action::make('mark_delivered')
                    ->label('Mark Delivered')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (VendorOrder $record): bool => VendorOrderStatus::OnTheWay === $record->status)
                    ->action(function (VendorOrder $record): void {
                        $record->update(['status' => VendorOrderStatus::Delivered]);
                        $record->customer->notify(new VendorOrderStatusChanged($record));
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
