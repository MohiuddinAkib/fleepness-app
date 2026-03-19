<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\SizeTemplate;
use App\Data\SizeTemplateData;
use App\Models\SizeTemplateItem;
use Illuminate\Http\JsonResponse;
use App\Data\SizeTemplateItemData;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;
use App\Data\SizeTemplate\StoreSizeTemplateData;
use Illuminate\Container\Attributes\CurrentUser;
use App\Data\SizeTemplate\StoreSizeTemplateItemData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SizeTemplateController extends Controller
{
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

    public function destroy(
        SizeTemplate $sizeTemplate,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($sizeTemplate->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $sizeTemplate->delete();

        return response()->json(['message' => 'Size template deleted.']);
    }

    public function storeItem(
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

    public function updateItem(
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

    public function destroyItem(
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
