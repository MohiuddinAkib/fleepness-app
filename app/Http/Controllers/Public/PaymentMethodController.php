<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\PaymentMethod;
use App\Data\PaymentMethodData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class PaymentMethodController extends Controller
{
    public function index(): JsonResponse
    {
        $methods = PaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Response::json([
            'data' => PaymentMethodData::collect($methods),
        ]);
    }
}
