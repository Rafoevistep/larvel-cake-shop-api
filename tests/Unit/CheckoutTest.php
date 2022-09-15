<?php

namespace Tests\Unit;

use Tests\TestCase;


class CheckoutTest extends TestCase
{
    public function test_cart_checkout()
    {
        $this->loginSingleUser();

        $addToCart = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post('api/auth/cart/10', [
                'qty' => 2
            ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/checkout', [
                'flat' => 'flatFlat',
                'street_name' => 'Street Name',
                'area' => 'areaArea',
                'landmark' => 'landmark',
                'city' => 'Yerevan',
                'payment_method' => 'cash_on_delivery'
            ]);

        $response->assertStatus(200);
    }

    public function test_single_product_checkout()
    {
        $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('/api/auth/checkout/211', [
                'flat' => 'flatFlat',
                'street_name' => 'Street Name',
                'area' => 'areaArea',
                'landmark' => 'landmark',
                'city' => 'Yerevan',
                'payment_method' => 'cash_on_delivery',
                'qty' => 1
            ]);

        $response->assertStatus(200);
    }

    public function test_cancel_order()
    {
        $this->loginSingleUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('api/auth/myorder/cancel/12', [
                'status' => 'cancelled',
            ]);

        $response->assertStatus(200);
    }

    public function test_my_orders()
    {
        $this->loginSingleUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/auth/myorder');

        $response->assertStatus(200);
    }


    public function test_search_order()
    {
        $response = $this->get('api/order/search/f78bdcd9-5645-4ca7-8149-343c7e21c34c');
        $response->assertStatus(200);
    }

    public function test_my_order_download()
    {
        $this->loginSingleUser();
        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->get('api/auth/myorder/order_export');

//        dd($response->getData());
//        $response->assertDownload();
        $response->assertStatus(200);

    }


}
