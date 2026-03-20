<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\DeliveryOption;
use App\Data\DeliveryOptionData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Discovery', 'Reference data used during checkout and storefront browsing.')]
class DeliveryOptionController extends Controller
{
    #[Endpoint('List delivery options')]
    #[Response('{"data":[{"id":1,"name":"Standard Delivery","fee":"60.00"}]}', 200)]
    #[Unauthenticated]
    /** @return DataCollection<DeliveryOptionData> */
    public function index(): JsonResponse|Responsable
    {
        $options = DeliveryOption::query()
            ->active()
            ->orderBy('fee')
            ->get();

        return DeliveryOptionData::collect($options, DataCollection::class);
    }
}
