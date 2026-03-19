<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\PaymentAccountData;
use Illuminate\Http\JsonResponse;
use App\Models\UserPaymentAccount;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use App\Data\Me\StorePaymentAccountData;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PaymentAccountController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $accounts = $user->paymentAccounts()->with('paymentMethod')->get();

        return PaymentAccountData::collect($accounts, DataCollection::class);
    }

    public function store(StorePaymentAccountData $data, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $account = $user->paymentAccounts()->create([
            'payment_method_id' => $data->paymentMethodId,
            'account_number' => $data->accountNumber,
            'is_primary' => false,
        ]);

        $account->load('paymentMethod');

        return PaymentAccountData::fromModel($account)->additional(['message' => 'Payment account added.']);
    }

    public function destroy(UserPaymentAccount $paymentAccount, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($paymentAccount->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $paymentAccount->delete();

        return response()->json(['message' => 'Payment account removed.']);
    }
}
