<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Slider;
use App\Models\Section;
use App\Data\SectionData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Discovery', 'Homepage discovery endpoints such as sections and sliders.')]
class SectionController extends Controller
{
    #[Endpoint('List visible sections')]
    #[Response('{"data":[{"id":1,"title":"Featured","items":[]}]}', 200)]
    #[Unauthenticated]
    public function index(): JsonResponse|Responsable
    {
        $sections = Section::query()
            ->where('is_visible', true)
            ->with('items')
            ->orderBy('sort_order')
            ->get();

        return SectionData::collect($sections, DataCollection::class);
    }

    #[Endpoint('List active sliders')]
    #[Response('{"data":[{"id":1,"image_url":"https://example.com/slider.jpg","url":"https://example.com/promo"}]}', 200)]
    #[Unauthenticated]
    public function sliders(): JsonResponse|Responsable
    {
        $sliders = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'data' => $sliders->map(fn (Slider $s) => [
                'id' => $s->getKey(),
                'image_url' => $s->getFirstMediaUrl('image') ?: null,
                'url' => $s->url,
            ])->values()->all(),
        ]);
    }
}
