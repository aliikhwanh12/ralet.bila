<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $waitingOrders = Order::where('status', Order::STATUS_WAITING)->count();
        $paidOrders = Order::where('status', Order::STATUS_PAID)->count();
        $revenue = Order::where('status', Order::STATUS_PAID)->sum('total_price');
        $activeProducts = Product::where('is_active', true)->count();

        $bestSellers = Product::withCount(['orders as sold' => function ($q) {
            $q->where('status', Order::STATUS_PAID);
        }])
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        $recentOrders = Order::latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'waitingOrders',
            'paidOrders',
            'revenue',
            'activeProducts',
            'bestSellers',
            'recentOrders'
        ));
    }
}
