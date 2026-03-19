<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;
use App\Data\Migration\LegacyEndpointDeprecationData;
use App\Actions\Api\ListLegacyEndpointDeprecationsAction;

#[Group('Migration', 'Migration helpers for clients moving from legacy aliases to the canonical versioned API.')]
class DeprecationController extends Controller
{
    #[Endpoint('List legacy endpoint deprecations', 'Returns the machine-readable mapping from legacy compatibility aliases to their preferred `/api/v1` replacements.')]
    #[Response('{"message":"Legacy endpoint deprecations retrieved.","data":[{"key":"me.role","legacy_path":"/api/me/role","methods":["GET"],"replacements":["/api/v1/me/summaries","/api/v1/me/vendors"]}]}', 200)]
    #[Unauthenticated]
    public function index(
        ListLegacyEndpointDeprecationsAction $listLegacyEndpointDeprecations,
    ): JsonResponse|Responsable {
        return response()->json([
            'message' => 'Legacy endpoint deprecations retrieved.',
            'data' => LegacyEndpointDeprecationData::collect(
                $listLegacyEndpointDeprecations->execute(),
                DataCollection::class
            ),
        ]);
    }
}
