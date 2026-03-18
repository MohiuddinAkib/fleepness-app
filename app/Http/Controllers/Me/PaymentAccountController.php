<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Attributes\CurrentUser;
use App\Data\PaymentAccountData;
use Illuminate\Http\JsonResponse;
use App\Models\UserPaymentAccount;
use App\Http\Controllers\Controller;
use App\Data\Me\StorePaymentAccountData;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PaymentAccountController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $accounts = $user->paymentAccounts()->with('paymentMethod')->get();

        return Response::json([
            'data' => PaymentAccountData::collect($accounts),
        ]);
    }

    public function store(StorePaymentAccountData $data, #[CurrentUser] User $user): JsonResponse
    {
        $account = $user->paymentAccounts()->create([
            'payment_method_id' => $data->paymentMethodId,
            'account_number' => $data->accountNumber,
            'is_primary' => false,
        ]);

        $account->load('paymentMethod');

        return Response::json([
            'message' => 'Payment account added.',
            'data' => PaymentAccountData::fromModel($account),
        ], HttpResponse::HTTP_CREATED);
    }

    public function destroy(UserPaymentAccount $paymentAccount, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($paymentAccount->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $paymentAccount->delete();

        return Response::json(['message' => 'Payment account removed.']);
    }
}
