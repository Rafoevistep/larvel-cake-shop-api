<?php

namespace Tests\Unit;

use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_add_to_cart()
    {
        $this->loginSingleUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post('api/auth/cart/10', [
                'qty' => 2
            ]);

        $response->assertStatus(200);

    }

    public function test_delete_from_cart()
    {
        $this->loginSingleUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->delete('api/auth/cart/39');

        $response->assertStatus(200);

    }

    public function test_get_cart()
    {
        $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->get('api/auth/cart');

        $response->assertStatus(200);

    }
}
