<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Data\Me\AccountSummaryData;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Actions\Me\GetAccountSummaryAction;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Profile', 'Read authenticated account summary data used by clients to branch on role and vendor state.')]
class AccountSummaryController extends Controller
{
    #[Authenticated]
    #[Endpoint('Get account summaries')]
    #[Response('{"data":{"user_id":1,"name":"Vendor User","role":"vendor","status":"approved"}}', 200)]
    /** @return JsonResponse<array{data: AccountSummaryData}> */
    public function index(
        #[CurrentUser] User $user,
        GetAccountSummaryAction $getAccountSummary,
    ): JsonResponse|Responsable {
        return response()->json([
            'data' => $getAccountSummary->execute($user),
        ]);
    }
}
