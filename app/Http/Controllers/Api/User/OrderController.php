<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class OrderController extends Controller
{

    public function index()
    {
        $total_orders = Order::all();

        return response()->json(['message' => 'Total Orders', $total_orders]);
    }


    public function store(Request $request, Cart $cart)
    {
        //User Can Enter Information
        $order_number = Str::uuid();

        $user_id = auth('sanctum')->user()->id;

        $cart_items = Cart::find(['user_id' => $user_id, 'product_id' => $cart->id]);


        $validator = Validator::make($request->all(), [
            'flat' => 'required|string|min:5',
            'street_name' => 'required|string|min:5',
            'area' => 'required|string|min:5',
            'landmark' => 'required|string|min:5',
            'city' => 'required|string|min:3',
        ]);

        $checkout = Order::create([
            'order_number' => $order_number,
            'user_id' => $user_id,
            'flat' => $request->flat,
            'street_name' => $request->street_name,
            'area' => $request->area,
            'landmark' => $request->landmark,
            'city' => $request->city,
        ]);


        $validator->validated();

        return response()->json(['message' => 'Your Order Placed Succesfuly', $checkout, $cart_items]);
    }

    public function show($id)
    {
        $order = Order::find($id);
        $cart_items = Cart::find($order);

        if (!$order) {
            return response()->json([
                'message' => 'Order Not Found.'
            ], 404);
        }
        return response()->json([
            'message' => 'Single Order',
            'order' => $order,
            'cart_item' => $cart_items
        ]);
    }

    public function update(Request $request, $id)
    {
        //Admin Change status Order

        $order = Order::find($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|',
        ]);

        $order->status = $request->status;
        $validator->validated();

        if ($order->save()) {
            return response()->json([
                'message' => 'Status Succesfily Updated',
                $order
            ], 200);
        }

        $validator->validated();
    }

    public function cancel(Order $order)
    {
        if (auth('sanctum')->user()) {
            $order->status = 'cancelled';
            $order->save();

            //check if all suborders are canceled
            $pendingSubOrders = $order->where('status', '!=', 'cancelled')->count();

            if ($pendingSubOrders == 0) {
                $order->order()->update(['status' => 'canceled']);
            }

            return response()->json(['message' => 'Your Order Cancled']);
        } else {
            return response()->json(['message' => 'Acton Forbebidden']);
        }
    }

    // public function destroy($id)
    // {
    //     //
    // }

    public function myorder()
    {
        //User Orders
        $user_id = auth('sanctum')->user()->id;

        $order = Order::where(['user_id' => $user_id])->get();

        if ($order) {
            return response()->json([
                'message' => 'Your orders',
                'cart' => $order,
            ]);
        } else {
            return response()->json(['message' => 'Your Can  Not Order']);
        }
    }

    function search($order)
    {
        $result = Order::where('order_number', 'LIKE', '%' . $order . '%')->get();

        if (count($result)) {
            return Response()->json($result);
        } else {
            return response()->json(['Result' => 'No Data not found'], 404);
        }
    }
}
