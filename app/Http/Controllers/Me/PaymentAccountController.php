<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\PaymentAccountData;
use Illuminate\Http\JsonResponse;
use App\Models\UserPaymentAccount;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use App\Data\Me\StorePaymentAccountData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Payment Accounts', 'Manage the user\'s linked payment accounts for withdrawals.')]
class PaymentAccountController extends Controller
{
    #[Authenticated]
    #[Endpoint('List payment accounts')]
    #[Response('{"data": [{"id": 1, "account_number": "01712345678", "is_primary": true}]}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $accounts = $user->paymentAccounts()->with('paymentMethod')->get();

        return PaymentAccountData::collect($accounts, DataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('payment_method_id', 'integer', required: true, example: 1)]
    #[BodyParam('account_number', 'string', required: true, example: '01712345678')]
    #[BodyParam('is_primary', 'boolean', required: false, example: false)]
    #[Endpoint('Add payment account')]
    #[Response('{"data": {"id": 2, "account_number": "01712345678"}}', 201)]
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

    #[Authenticated]
    #[Endpoint('Remove payment account')]
    #[Response('{"message": "Payment account removed."}', 200)]
    public function destroy(UserPaymentAccount $paymentAccount, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($paymentAccount->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $paymentAccount->delete();

        return response()->json(['message' => 'Payment account removed.']);
    }
}
