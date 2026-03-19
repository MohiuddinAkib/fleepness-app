<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SizeTemplate;
use Illuminate\Http\Request;
use App\Models\SizeTemplateItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Responsable;

class SizeTemplateController extends Controller
{
    // Create a size template
    public function store(Request $request)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
        ]);

        $template = SizeTemplate::create([
            'seller_id' => auth()->id(),
            'template_name' => $request->template_name,
        ]);

        return response()->json([
            'message' => 'Size template created successfully',
            'template' => $template,
        ], 201);
    }

    public function storeItem(Request $request, SizeTemplate $sizeTemplate): JsonResponse|Responsable
    {
        abort_unless($sizeTemplate->seller_id === auth()->id(), 403, 'Unauthorized');

        $request->validate([
            'sizes' => 'required|array|min:1',
            'sizes.*.size_name' => 'required|string|max:50',
            'sizes.*.size_value' => 'required|string|max:255',
        ]);

        $createdSizes = [];

        foreach ($request->sizes as $size) {
            $createdSizes[] = SizeTemplateItem::create([
                'template_id' => $sizeTemplate->getKey(),
                'size_name' => $size['size_name'],
                'size_value' => $size['size_value'],
            ]);
        }

        return response()->json([
            'message' => 'Sizes added to template',
            'size_items' => $createdSizes,
        ], 201);
    }

    public function updateItem(Request $request, SizeTemplate $sizeTemplate, SizeTemplateItem $sizeTemplateItem): JsonResponse|Responsable
    {
        abort_unless($sizeTemplate->seller_id === auth()->id(), 403, 'Unauthorized');
        abort_unless($sizeTemplateItem->template_id === $sizeTemplate->getKey(), 404, 'Size item not found');

        $request->validate([
            'size_name' => 'nullable|string|max:50',
            'size_value' => 'nullable|string|max:255',
        ]);

        if ($request->has('size_name')) {
            $sizeTemplateItem->size_name = $request->size_name;
        }

        if ($request->has('size_value')) {
            $sizeTemplateItem->size_value = $request->size_value;
        }

        $sizeTemplateItem->save();

        return response()->json([
            'message' => 'Size item updated successfully',
            'size_item' => $sizeTemplateItem,
        ]);
    }

    public function destroyItem(SizeTemplate $sizeTemplate, SizeTemplateItem $sizeTemplateItem): JsonResponse|Responsable
    {
        abort_unless($sizeTemplate->seller_id === auth()->id(), 403, 'Unauthorized');
        abort_unless($sizeTemplateItem->template_id === $sizeTemplate->getKey(), 404, 'Size item not found');

        $sizeTemplateItem->delete();

        return response()->json([
            'message' => 'Size item deleted successfully',
        ]);
    }

    public function index(): JsonResponse|Responsable
    {
        $templates = SizeTemplate::with('items')
            ->where('seller_id', auth()->id())
            ->get();

        return response()->json($templates);
    }

    public function destroy(SizeTemplate $sizeTemplate): JsonResponse|Responsable
    {
        abort_unless($sizeTemplate->seller_id === auth()->id(), 403, 'Unauthorized');

        try {
            SizeTemplateItem::where('template_id', $sizeTemplate->getKey())->delete();
            $sizeTemplate->delete();

            return response()->json([
                'message' => 'Size template deleted successfully',
            ]);
        } catch (QueryException $e) {
            if ('23000' === $e->getCode()) {
                return response()->json([
                    'message' => 'Cannot delete this template because it is used in one or more products.',
                ], 409);
            }

            return response()->json([
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
