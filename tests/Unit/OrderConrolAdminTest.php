<?php

namespace Tests\Unit;

use Tests\TestCase;

class OrderConrolAdminTest extends TestCase
{
    //Admin Test Change Order status

    public function test_admin_change_order_status_completed()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('api/admin/checkout/26', [
                'status' => 'completed',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_cancelled()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('api/admin/checkout/27', [
                'status' => 'cancelled',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_being_prepeared()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('api/admin/checkout/28', [
                'status' => 'being_prepeared',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_pickup()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('api/admin/checkout/28', [
                'status' => 'pickup',
            ]);

        $response->assertStatus(200);
    }

    public function test_admin_change_order_status_deleveried()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('api/admin/checkout/29', [
                'status' => 'deleveried',
            ]);

        $response->assertStatus(200);
    }

    public function  test_admin_get_all_orders()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/checkout/');

        $response->assertStatus(200);

    }

    public function  test_admin_get_single_order()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/checkout/60');

        $response->assertStatus(200);

    }
}
