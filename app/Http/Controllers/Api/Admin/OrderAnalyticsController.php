<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;

class OrderAnalyticsController extends Controller
{
    public function total(): \Illuminate\Http\JsonResponse
    {
        $total_orders = Order::all();

        return response()->json([
            'message' => 'Total Orders', count($total_orders),
            $total_orders
        ]);
    }

    public function notConfirmed(): \Illuminate\Http\JsonResponse
    {
        $not_confirmed = Order::where(['status' => 'pending'])->get();

        return response()->json([
            'message' => 'Total Orders', count($not_confirmed),
            $not_confirmed
        ]);
    }

    public function cancelled(): \Illuminate\Http\JsonResponse
    {
        $cancelled = Order::where(['status' => 'cancelled'])->get();

        return response()->json([
            'message' => 'Total Orders', count($cancelled),
            $cancelled
        ]);
    }

    public function completed(): \Illuminate\Http\JsonResponse
    {
        $completed = Order::where(['status' => 'completed'])->get();

        return response()->json([
            'message' => 'Total Orders', count($completed),
            $completed
        ]);
    }

    public function prepeared(): \Illuminate\Http\JsonResponse
    {
        $prepeared = Order::where(['status' => 'being_prepeared'])->get();

        return response()->json([
            'message' => 'Total Orders', count($prepeared),
            $prepeared
        ]);
    }

    public function pickup(): \Illuminate\Http\JsonResponse
    {
        $pickup = Order::where(['status' => 'pickup'])->get();

        return response()->json([
            'message' => 'Total Orders', count($pickup),
            $pickup
        ]);
    }

    public function deleveried(): \Illuminate\Http\JsonResponse
    {
        $deleveried = Order::where(['status' => 'deleveried'])->get();

        return response()->json([
            'message' => 'Total Orders', count($deleveried),
            $deleveried
        ]);
    }

    public function showSales(): \Illuminate\Http\JsonResponse
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
