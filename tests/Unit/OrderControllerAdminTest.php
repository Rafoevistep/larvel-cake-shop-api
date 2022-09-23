<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderControllerAdminTest extends TestCase
{
    //Admin Test Change Order status
    use WithFaker;

    public function order_create()
    {
        $user = $this->loginAdmin();

        $product = Product::factory()->create();

        $order = $this
            ->withHeader('Authorization', 'Bearer ' . $user['token'])
            ->withHeader('Accept', 'application/json')
            ->post("/api/auth/checkout/{$product->id}", [
                'user_id' => $this->authUser['id'],
                'flat' => $this->faker->city,
                'street_name' => $this->faker->streetName,
                'area' => $this->faker->streetName,
                'landmark' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'payment_method' => 'cash_on_delivery',
                'qty' => $this->faker->numberBetween(10, 20),
            ]);

        $response = $this->get('api/auth/logout', [
            'Authorization' => "Bearer $this->authToken"
        ]);

        return $order['order']['id'];

    }

    public function test_order()
    {
        $this->loginUser();

        $qty = $this->faker->numberBetween(10, 20);
        $product = Product::factory()->create();
        $total = $qty * $product->price;

        $order = Order::create([
            'order_number' => Str::uuid(),
            'user_id' => $this->authUser['id'],
            'flat' => $this->faker->city,
            'street_name' => $this->faker->streetName,
            'area' => $this->faker->streetName,
            'landmark' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'payment_method' => 'cash_on_delivery',
            'qty' => $qty,
            'total' => $total,
        ]);

        return $order['id'];

    }

    public function test_admin_change_order_status_completed()
    {
        $orderId = $this->test_order();

        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("api/admin/checkout/$orderId", [
                'status' => 'completed',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_cancelled()
    {
        $orderId = $this->test_order();

        $this->loginAdmin();
        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("api/admin/checkout/$orderId", [
                'status' => 'cancelled',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_being_prepeared()
    {
        $orderId = $this->test_order();

        $this->loginAdmin();


        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("api/admin/checkout/$orderId", [
                'status' => 'being_prepeared',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_pickup()
    {
        $orderId = $this->test_order();

        $this->loginAdmin();


        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("api/admin/checkout/$orderId", [
                'status' => 'pickup',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_deleveried()
    {
        $orderId = $this->test_order();

        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("api/admin/checkout/$orderId", [
                'status' => 'deleveried',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_get_all_orders()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/checkout');

        $response->assertStatus(200);

    }

    public function test_admin_get_single_order()
    {
        $orderId = $this->order_create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get("api/admin/checkout/$orderId");

        $response->assertStatus(200);

    }
}
