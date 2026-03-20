<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\SizeTemplate;
use App\Data\SizeTemplateData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Response\MessageResponseData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Data\SizeTemplate\StoreSizeTemplateData;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Size Templates', 'Manage reusable size templates for products. A size template groups labelled size options (e.g. S, M, L) with their values (e.g. chest 36–38 inches).')]
class SizeTemplateController extends Controller
{
    #[Authenticated]
    #[Endpoint('List size templates')]
    #[Response('{"data": [{"id": 1, "name": "Shirt Sizes", "items": [{"label": "M", "value": "38-40 inches"}]}]}', 200)]
    /** @return DataCollection<SizeTemplateData> */
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $templates = SizeTemplate::query()
            ->where('vendor_profile_id', $vendorProfile->getKey())
            ->with('items')
            ->get();

        return SizeTemplateData::collect($templates, DataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('name', 'string', required: true, example: 'Shirt Sizes')]
    #[Endpoint('Create size template')]
    #[Response('{"data": {"id": 1, "name": "Shirt Sizes"}}', 201)]
    /** @return SizeTemplateData */
    public function store(
        StoreSizeTemplateData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $template = SizeTemplate::query()->create([
            'vendor_profile_id' => $vendorProfile->getKey(),
            'name' => $data->name,
        ]);

        $template->load('items');

        return SizeTemplateData::fromModel($template);
    }

    /** @return MessageResponseData */
    public function destroy(
        SizeTemplate $sizeTemplate,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($sizeTemplate->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $sizeTemplate->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Size template deleted.',
        ])->toArray());
    }
}
