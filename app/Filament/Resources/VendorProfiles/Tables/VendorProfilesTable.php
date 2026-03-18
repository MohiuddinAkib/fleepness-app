<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorProfiles\Tables;

use Filament\Tables\Table;
use App\Enums\VendorStatus;
use Filament\Actions\Action;
use App\Models\VendorProfile;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Notifications\VendorStatusUpdated;

class VendorProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shop_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable(),
                TextColumn::make('shopCategory.name')
                    ->label('Category')
                    ->default('—'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (VendorStatus $state): array|string => match ($state) {
                        VendorStatus::Approved => 'success',
                        VendorStatus::Rejected => 'danger',
                        VendorStatus::Pending => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('balance')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('order_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(VendorStatus::class),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color(Color::Green)
                    ->requiresConfirmation()
                    ->visible(fn (VendorProfile $record): bool => VendorStatus::Pending === $record->status)
                    ->action(function (VendorProfile $record): void {
                        $record->update(['status' => VendorStatus::Approved]);
                        $record->load('user');
                        $record->user->notify(VendorStatusUpdated::approved());
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color(Color::Red)
                    ->requiresConfirmation()
                    ->visible(fn (VendorProfile $record): bool => VendorStatus::Pending === $record->status)
                    ->action(function (VendorProfile $record): void {
                        $record->update(['status' => VendorStatus::Rejected]);
                        $record->load('user');
                        $record->user->notify(VendorStatusUpdated::rejected());
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
