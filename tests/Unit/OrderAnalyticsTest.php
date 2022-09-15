<?php

namespace Tests\Unit;

use Tests\TestCase;

class OrderAnalyticsTest extends TestCase
{
    public function  test_admin_show_total_orders()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/total');

        $response->assertStatus(200);

    }



    public function  test_admin_show_not_confirmed()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/notconfirmed');

        $response->assertStatus(200);

    }

    public function  test_admin_show_cancelled()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/cancelled');

        $response->assertStatus(200);

    }

    public function  test_admin_show_completed()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/completed');

        $response->assertStatus(200);

    }

    public function  test_admin_show_total_prepeared()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/prepeared');

        $response->assertStatus(200);

    }

    public function  test_admin_show_pickup()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/pickup');

        $response->assertStatus(200);

    }

    public function  test_admin_show_deleveried()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/deleveried');

        $response->assertStatus(200);

    }

    public function  test_admin_show_sales()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/deleveried');

        $response->assertStatus(200);

    }

}
