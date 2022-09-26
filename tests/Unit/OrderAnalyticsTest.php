<?php

namespace Tests\Unit;

use Tests\TestCase;

class OrderAnalyticsTest extends TestCase
{
    const ANALYTIC_STRUCTURE = [
        "id",
        "order_number",
        "user_id",
        "status",
        "is_paid",
        "payment_method",
        "total",
        "flat",
        "street_name",
        "area",
        "landmark",
        "city",
        "created_at",
        "updated_at"
    ];

    public function test_admin_show_total_orders()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/total')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);
    }


    public function test_admin_show_not_confirmed()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/notconfirmed')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);

    }

    public function test_admin_show_cancelled()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/cancelled')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);

    }

    public function test_admin_show_completed()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/completed')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);

    }

    public function test_admin_show_total_prepeared()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/prepeared')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);
    }

    public function test_admin_show_pickup()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/pickup')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);
    }

    public function test_admin_show_deleveried()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/deleveried')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);
    }

    public function test_admin_show_sales()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/admin/order/alytics/deleveried')
            ->assertStatus(200)
            ->assertJsonStructure([
                "count",
                "list" => [
                    "*" => self::ANALYTIC_STRUCTURE
                ]
            ]);
    }

}
