<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\SizeTemplate;
use App\Models\SizeTemplateItem;
use Illuminate\Http\JsonResponse;
use App\Data\SizeTemplateItemData;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use App\Data\SizeTemplate\StoreSizeTemplateItemData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SizeTemplateItemController extends Controller
{
    public function index(SizeTemplate $sizeTemplate): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    #[Authenticated]
    public function store(
        StoreSizeTemplateItemData $data,
        SizeTemplate $sizeTemplate,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($sizeTemplate->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $item = $sizeTemplate->items()->create([
            'label' => $data->label,
            'value' => $data->value,
        ]);

        return SizeTemplateItemData::fromModel($item);
    }

    public function show(SizeTemplate $sizeTemplate, SizeTemplateItem $sizeTemplateItem): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    #[Authenticated]
    public function update(
        StoreSizeTemplateItemData $data,
        SizeTemplate $sizeTemplate,
        SizeTemplateItem $sizeTemplateItem,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($sizeTemplate->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($sizeTemplateItem->sizeTemplate()->is($sizeTemplate), HttpResponse::HTTP_NOT_FOUND);

        $sizeTemplateItem->update([
            'label' => $data->label,
            'value' => $data->value,
        ]);

        return SizeTemplateItemData::fromModel($sizeTemplateItem);
    }

    #[Authenticated]
    public function destroy(
        SizeTemplate $sizeTemplate,
        SizeTemplateItem $sizeTemplateItem,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($sizeTemplate->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($sizeTemplateItem->sizeTemplate()->is($sizeTemplate), HttpResponse::HTTP_NOT_FOUND);

        $sizeTemplateItem->delete();

        return response()->json(['message' => 'Item deleted.']);
    }
}
