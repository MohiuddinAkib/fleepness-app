<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\PaymentMethod;
use App\Data\PaymentMethodData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;

class PaymentMethodController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $methods = PaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return PaymentMethodData::collect($methods, DataCollection::class);
    }
}
