<?php

namespace App\Http\Controllers\Api\User;

use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Excel;


class OrderController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $total_orders = Order::all();

        return response()->json([
            'total' => $total_orders
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'flat' => 'required|string|min:5',
            'street_name' => 'required|string|min:5',
            'area' => 'required|string|min:5',
            'landmark' => 'required|string|min:5',
            'city' => 'required|string|min:3',
            'total' => 'required|integer',
            'qty' => 'required|integer|min:1'
        ]);

        $validator->validated();

        //User Can Enter Information
        $order_number = Str::uuid();

        $user_id = auth('sanctum')->user()->id;

        $cart = Cart::where('user_id', $user_id)->get()->all();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty.'
            ]);
        };

        $checkout = Order::create([
            'order_number' => $order_number,
            'user_id' => $user_id,
            'flat' => $request->flat,
            'street_name' => $request->street_name,
            'area' => $request->area,
            'landmark' => $request->landmark,
            'city' => $request->city,
            'payment_method' => $request->payment_method,
        ]);

        $orderItems = [];
        $total = 0;

        foreach ($cart as $cartProduct) {
            $orderItems[] = [
                'order_id' => $checkout->id,
                'product_id' => $cartProduct->product_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'qty' => $cartProduct->qty,
            ];
            $total += $cartProduct->price * $cartProduct->qty;
        }

        OrderItem::insert($orderItems);

        $checkout->total = $total;
        $checkout->save();

        Cart::where('user_id', $user_id)->delete();

        return response()->json([
            'message' => 'Your Order Placed Successfully',
            'total' => $total,
            'order' => $checkout,
        ]);
    }


    public function storeSingle(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'flat' => 'required|string|min:5',
            'street_name' => 'required|string|min:5',
            'area' => 'required|string|min:5',
            'landmark' => 'required|string|min:5',
            'city' => 'required|string|min:3',
            'qty' => 'required|integer|min:1'
        ]);

        $validator->validated();

        $order_number = Str::uuid();

        $user_id = auth('sanctum')->user()->id;

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        $checkout = Order::create([
            'order_number' => $order_number,
            'user_id' => $user_id,
            'flat' => $request->flat,
            'street_name' => $request->street_name,
            'area' => $request->area,
            'landmark' => $request->landmark,
            'city' => $request->city,
            'payment_method' => $request->payment_method,
            'qty' => $request->qty,
            'total' => $product->price
        ]);

        if ($checkout) {
            $order_item = OrderItem::create([
                'order_id' => $checkout->id,
                'product_id' => $product->id,
                'qty' => $request->qty
            ]);
        }

        return response()->json([
            'message' => 'Your Order Placed Successfully',
            'order' => $checkout,
        ]);
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $items = OrderItem::where('order_id', $id)->with('product')->get()->toArray();

        $order = Order::find($id);

        if (!$items) {
            return response()->json([
                'message' => 'Order Not Found.'
            ], 404);
        }

        return response()->json([
            'order' => $order,
            'product' => $items,
        ]);
    }


    public function update(Request $request, $id)
    {
        //Admin Change status Order
        $order = Order::find($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|',
        ]);

        $validator->validated();

        if (!$order) {
            return response()->json([
                'message' => 'Order Not Found.'
            ], 404);
        }

        $order->status = $request->status;

        $order->save();

        return response()->json([
            'message' => 'Status Successfully Updated',
            'order' => $order
        ], 200);

    }

    public function cancel(Order $order): \Illuminate\Http\JsonResponse
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

    public function myorder(): \Illuminate\Http\JsonResponse
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

    function search($order): \Illuminate\Http\JsonResponse
    {
        $result = Order::where('order_number', 'LIKE', '%' . $order . '%')->get();

        if (count($result)) {
            return response()->json($result);
        } else {
            return response()->json(['Result' => 'No Data not found'], 404);
        }
    }

    public function get_orders_data()
    {
        return Excel::download(new OrderExport, 'order.xlsx');
    }
}
