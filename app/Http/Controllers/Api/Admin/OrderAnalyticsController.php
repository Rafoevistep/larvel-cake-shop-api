<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAnalyticsController extends Controller
{
    public function total()
    {
        $total_orders = Order::all();

        return response()->json([
            'message' => 'Total Orders', count($total_orders),
            $total_orders
        ]);
    }


    public function notConfirmed()
    {
        $not_confirmed = Order::where(['status' => 'pending'])->get();

        return response()->json([
            'message' => 'Total Orders', count($not_confirmed),
            $not_confirmed
        ]);
    }

    public function  cancelled()
    {
        $cancelled = Order::where(['status' => 'cancelled'])->get();

        return response()->json([
            'message' => 'Total Orders', count($cancelled),
            $cancelled
        ]);
    }


    public function completed()
    {
        $completed = Order::where(['status' => 'completed'])->get();

        return response()->json([
            'message' => 'Total Orders', count($completed),
            $completed
        ]);
    }

    public function prepeared()
    {
        $prepeared = Order::where(['status' => 'being_prepeared'])->get();

        return response()->json([
            'message' => 'Total Orders', count($prepeared),
            $prepeared
        ]);
    }


    public function pickup()
    {
        $pickup = Order::where(['status' => 'pickup'])->get();

        return response()->json([
            'message' => 'Total Orders', count($pickup),
            $pickup
        ]);
    }

    public function  deleveried()
    {
        $deleveried = Order::where(['status' => 'deleveried'])->get();

        return response()->json([
            'message' => 'Total Orders', count($deleveried),
            $deleveried
        ]);
    }
}
