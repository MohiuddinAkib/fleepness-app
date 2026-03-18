<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\SellerOrder;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {

        $data['pending_order'] = SellerOrder::where('status', 'pending')
            ->count();
        $data['total_sales'] = Order::sum('product_cost');
        $data['revenue'] = Order::sum('balance');
        $data['total_products'] = Product::all()->count();

        return view('admin.index', $data);
    }
}
