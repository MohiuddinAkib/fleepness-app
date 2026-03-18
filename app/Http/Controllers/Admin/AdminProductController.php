<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    public function index()
    {
        $data['products'] = Product::with('category')->latest()->whereNull('deleted_at')->get();
        $data['categories'] = Category::whereNull('parent_id')->get();

        return view('admin.products.index', $data);
    }

    // public function create()
    // {
    //     $categories = Category::all();
    //     return view('products.create', compact('categories'));
    // }

    protected function generateUniqueCode()
    {
        do {
            $code = random_int(100000, 999999);
        } while (Product::where('code', $code)->exists());

        return $code;
    }

    public function store(Request $request) {}

    public function edit(Product $product)
    {
        // dd($product);
        $productImageCount = ProductImage::where('product_id', $product->id)->count();
        $categories = Category::whereNull('parent_id')->get();

        return view('admin.products.product_edit', compact('product', 'categories', 'productImageCount'));
    }

    public function update(Request $request, Product $product)
    {
        $productImageCount = ProductImage::where('product_id', $product->id)->count();
        if (0 === $productImageCount && null === $request->hasFile('images')) {
            return back()->with('success', 'Please add updated images.');
        }
        $validated = $request->validate([]);
        $validated['admin_approval'] = $request->admin_approval;
        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $order = OrderItem::where('product_id', $product->id)->count('id');
        if (0 < $order) {
            return redirect()->route('admin.products.index')->with('success', 'You can not delete this Product . Because Under this product First delete Order.');
        }

        $product->deleted_at = Carbon::now();
        $product->admin_approval = 'suspended';
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product suspended successfully.');
    }

    public function ImageDelete($id) {}

    public function StockDelete($id) {}
}
