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
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Transaction\StoreWithdrawalData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Transactions', 'View wallet transactions and request withdrawals.')]
class TransactionController extends Controller
{
    #[Authenticated]
    #[Endpoint('List transactions')]
    #[Response('{"data":[{"id":1,"type":"withdrawal","status":"pending","amount":"500.00"}],"meta":{"current_page":1}}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $transactions = Transaction::query()
            ->where('user_id', $user->getKey())
            ->latest()
            ->paginate();

        return TransactionData::collect($transactions, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('payment_method_id', 'integer', required: true, example: 1)]
    #[BodyParam('amount', 'number', required: true, example: 500)]
    #[BodyParam('note', 'string', required: false, example: 'Weekly payout request')]
    #[Endpoint('Create withdrawal request')]
    #[Response('{"data":{"id":1,"type":"withdrawal","status":"pending","amount":"500.00"}}', 201)]
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
