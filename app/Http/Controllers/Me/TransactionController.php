<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Transaction;
use App\Data\TransactionData;
use App\Enums\TransactionType;
use App\Attributes\CurrentUser;
use App\Enums\TransactionStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Data\Transaction\StoreWithdrawalData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TransactionController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $transactions = Transaction::query()
            ->where('user_id', $user->getKey())
            ->latest()
            ->paginate();

        return Response::json(TransactionData::collect($transactions));
    }

    public function store(
        StoreWithdrawalData $data,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $transaction = Transaction::query()->create([
            'user_id' => $user->getKey(),
            'payment_method_id' => $data->paymentMethodId,
            'amount' => $data->amount,
            'type' => TransactionType::Withdrawal,
            'status' => TransactionStatus::Pending,
            'note' => $data->note instanceof Optional ? null : $data->note,
        ]);

        return Response::json(
            ['data' => TransactionData::fromModel($transaction)],
            HttpResponse::HTTP_CREATED
        );
    }
}
