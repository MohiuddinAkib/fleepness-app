<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\DeliveryOption;
use App\Data\DeliveryOptionData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class DeliveryOptionController extends Controller
{
    public function index(): JsonResponse
    {
        $options = DeliveryOption::query()
            ->where('is_active', true)
            ->orderBy('fee')
            ->get();

        return Response::json([
            'data' => DeliveryOptionData::collect($options),
        ]);
    }
}
