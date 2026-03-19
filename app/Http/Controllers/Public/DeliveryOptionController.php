<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\DeliveryOption;
use App\Data\DeliveryOptionData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;

class DeliveryOptionController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $options = DeliveryOption::query()
            ->where('is_active', true)
            ->orderBy('fee')
            ->get();

        return DeliveryOptionData::collect($options, DataCollection::class);
    }
}
