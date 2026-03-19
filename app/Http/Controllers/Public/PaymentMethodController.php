<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\PaymentMethod;
use App\Data\PaymentMethodData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Discovery', 'Reference data used during checkout and storefront browsing.')]
class PaymentMethodController extends Controller
{
    #[Endpoint('List payment methods')]
    #[Response('{"data":[{"id":1,"name":"Cash on Delivery"}]}', 200)]
    #[Unauthenticated]
    public function index(): JsonResponse|Responsable
    {
        $methods = PaymentMethod::query()
            ->active()
            ->orderBy('name')
            ->get();

        return PaymentMethodData::collect($methods, DataCollection::class);
    }
}
