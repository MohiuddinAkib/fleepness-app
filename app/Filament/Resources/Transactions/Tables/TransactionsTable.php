<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Actions\Action;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Notifications\WithdrawalApproved;
use Filament\Tables\Filters\SelectFilter;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('paymentMethod.name')
                    ->label('Method')
                    ->default('—'),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (TransactionType $state): string => match ($state) {
                        TransactionType::Withdrawal => 'warning',
                        TransactionType::Deposit => 'success',
                    }),
                TextColumn::make('amount')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (TransactionStatus $state): string => match ($state) {
                        TransactionStatus::Pending => 'warning',
                        TransactionStatus::Approved => 'success',
                        TransactionStatus::Rejected => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TransactionStatus::class),
                SelectFilter::make('type')
                    ->options(TransactionType::class),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color(Color::Green)
                    ->requiresConfirmation()
                    ->visible(fn (Transaction $record): bool => TransactionStatus::Pending === $record->status)
                    ->action(function (Transaction $record): void {
                        $record->update(['status' => TransactionStatus::Approved]);
                        $record->load('user');
                        $record->user->notify(new WithdrawalApproved($record));
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color(Color::Red)
                    ->requiresConfirmation()
                    ->visible(fn (Transaction $record): bool => TransactionStatus::Pending === $record->status)
                    ->action(fn (Transaction $record) => $record->update(['status' => TransactionStatus::Rejected])),
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
