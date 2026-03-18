<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Slider;
use App\Models\Section;
use App\Data\SectionData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class SectionController extends Controller
{
    public function index(): JsonResponse
    {
        $sections = Section::query()
            ->where('is_visible', true)
            ->with('items')
            ->orderBy('sort_order')
            ->get();

        return Response::json([
            'data' => SectionData::collect($sections),
        ]);
    }

    public function sliders(): JsonResponse
    {
        $sliders = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return Response::json([
            'data' => $sliders->map(fn (Slider $s) => [
                'id' => $s->getKey(),
                'image_url' => $s->getFirstMediaUrl('image') ?: null,
                'url' => $s->url,
            ])->values()->all(),
        ]);
    }
}
