<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Slider;
use App\Models\Section;
use App\Data\SectionData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;

class SectionController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $sections = Section::query()
            ->where('is_visible', true)
            ->with('items')
            ->orderBy('sort_order')
            ->get();

        return SectionData::collect($sections, DataCollection::class);
    }

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
