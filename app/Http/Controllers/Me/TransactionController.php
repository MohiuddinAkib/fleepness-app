<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Transaction;
use App\Data\TransactionData;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Transaction\StoreWithdrawalData;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;

class TransactionController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $transactions = Transaction::query()
            ->where('user_id', $user->getKey())
            ->latest()
            ->paginate();

        return TransactionData::collect($transactions, PaginatedDataCollection::class);
    }

    public function store(
        StoreWithdrawalData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $transaction = Transaction::query()->create([
            'user_id' => $user->getKey(),
            'payment_method_id' => $data->paymentMethodId,
            'amount' => $data->amount,
            'type' => TransactionType::Withdrawal,
            'status' => TransactionStatus::Pending,
            'note' => $data->note instanceof Optional ? null : $data->note,
        ]);

        return TransactionData::fromModel($transaction);
    }
}
