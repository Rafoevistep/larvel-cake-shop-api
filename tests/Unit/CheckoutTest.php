<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;


class CheckoutTest extends TestCase
{
    use WithFaker;

    public function test_cart_checkout()
    {
        $this->loginUser();

        $product = Product::factory()->create();

        $addToCart = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post("api/auth/cart/{$product->id}", [
                'qty' => 2
            ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/checkout', [
                'flat' => $this->faker->city,
                'street_name' => $this->faker->streetName,
                'area' => $this->faker->streetName,
                'landmark' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'payment_method' => 'cash_on_delivery',
                'qty' => $this->faker->numberBetween(10, 20),
            ]);

        $response->assertStatus(200);
    }

    public function test_single_product_checkout()
    {
        $this->loginUser();

        $product = Product::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post("/api/auth/checkout/{$product->id}", [
                'flat' => $this->faker->city,
                'street_name' => $this->faker->streetName,
                'area' => $this->faker->streetName,
                'landmark' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'payment_method' => 'cash_on_delivery',
                'qty' => $this->faker->numberBetween(10, 20),
            ]);

        $response->assertStatus(200);
    }

    public function test_cancel_order()
    {
        $this->loginUser();

        $order = Order::create([
                'order_number' => Str::uuid(),
                'user_id' => $this->authUser['id'],
                'flat' => $this->faker->city,
                'street_name' => $this->faker->streetName,
                'area' => $this->faker->streetName,
                'landmark' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'payment_method' => 'cash_on_delivery',
                'qty' => $this->faker->numberBetween(10, 20),
        ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("api/auth/myorder/cancel/$order->id", [
                'status' => 'cancelled',
            ]);

        $response->assertStatus(200);
    }

    public function test_my_orders()
    {
        $this->loginUser();


        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/auth/myorder');

        $response->assertStatus(200);
    }


    public function test_search_order()
    {
        $this->loginUser();

        $order = Order::create([
            'order_number' => Str::uuid(),
            'user_id' => $this->authUser['id'],
            'flat' => $this->faker->city,
            'street_name' => $this->faker->streetName,
            'area' => $this->faker->streetName,
            'landmark' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'payment_method' => 'cash_on_delivery',
            'qty' => $this->faker->numberBetween(10, 20),
        ]);

        $response = $this->get("api/order/search/$order->order_number");
        $response->assertStatus(200);
    }

    public function test_my_order_download()
    {
        $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/auth/myorder/order_export');

        $response->assertStatus(200);

    }


}
