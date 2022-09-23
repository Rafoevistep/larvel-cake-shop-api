<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderAnalyticsController extends Controller
{
    public function total(): JsonResponse
    {
        $total_orders = Order::all();

        return response()->json([
            'count' => count($total_orders),
            'list' => $total_orders
        ]);
    }

    public function notConfirmed(): JsonResponse
    {
        $not_confirmed = Order::where(['status' => 'pending'])->get();

        return response()->json([
            'count' => count($not_confirmed),
            'list' => $not_confirmed
        ]);
    }

    public function cancelled(): JsonResponse
    {
        $cancelled = Order::where(['status' => 'cancelled'])->get();

        return response()->json([
            'count' =>  count($cancelled),
            'list' => $cancelled
        ]);
    }

    public function completed(): JsonResponse
    {
        $completed = Order::where(['status' => 'completed'])->get();

        return response()->json([
            'count' => count($completed),
            'list' =>  $completed
        ]);
    }

    public function prepeared(): JsonResponse
    {
        $prepeared = Order::where(['status' => 'being_prepeared'])->get();

        return response()->json([
            'count' =>  count($prepeared),
            'list' =>  $prepeared
        ]);
    }

    public function pickup(): JsonResponse
    {
        $pickup = Order::where(['status' => 'pickup'])->get();

        return response()->json([
            'count' =>  count($pickup),
            'list' =>  $pickup
        ]);
    }

    public function deleveried(): JsonResponse
    {
        $deleveried = Order::where(['status' => 'deleveried'])->get();

        return response()->json([
            'count' =>  count($deleveried),
            'list' => $deleveried
        ]);
    }

    public function showSales(): JsonResponse
    {
        $order = Order::all();

        $cart_items = Cart::query($order)->get();

        $total = $cart_items->map(function ($product) {
            return $product->price * $product->qty;
        })->sum();

        $countOrder = count($order);

        $grandTotal = $countOrder * $total;

        return response()->json([
            'Total Orders' => count($order),
            'Total Sales' => $grandTotal,
            'Order Details' => $order,
        ]);
    }
}
