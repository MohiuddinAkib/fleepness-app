<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Tag;
use App\Data\TagData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tags = Tag::query()->orderBy('name')->get();

        return Response::json([
            'data' => TagData::collect($tags),
        ]);
    }
}
