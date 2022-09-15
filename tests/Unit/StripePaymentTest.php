<?php

namespace Tests\Unit;

use Tests\TestCase;

class StripePaymentTest extends TestCase
{
    public function test_single_product_checkoutStripe()
    {
        $this->loginSingleUser();
        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/stripe/3', [
                'number' => '4242424242424242',
                'exp_month' => '12',
                'exp_year' => '2022',
                'cvc' => '311',
                'description' => 'new york',
            ]);

        $response->assertStatus(201);
    }

    public function test_single_product_checkoutStripe_validation()
    {
        $this->loginSingleUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/stripe/3', [
                'number' => '4242424242424242',
                'exp_month' => '12',
                'exp_year' => '2014',
                'cvc' => '311',
                'description' => 'new york',
            ]);

        $response->assertStatus(422);
    }

    public function test_cart_product_checkoutStripe()
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
            ->post('api/auth/stripe', [
                'number' => '4242424242424242',
                'exp_month' => '12',
                'exp_year' => '2022',
                'cvc' => '311',
                'description' => 'new york',
            ]);

        $response->assertStatus(201);
    }

    public function test_cart_product_checkoutStripe_validation()
    {
        $this->loginSingleUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/stripe', [
                'number' => '4242424242424242',
                'exp_month' => '17',
                'exp_year' => '2010',
                'cvc' => '311',
                'description' => 'new york',
            ]);

        $response->assertStatus(422);
    }

    public function test_cart_product_checkoutStripe_empty_cart()
    {
        $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/stripe', [
                'number' => '4242424242424242',
                'exp_month' => '12',
                'exp_year' => '2022',
                'cvc' => '311',
                'description' => 'new york',
            ]);

        $response->assertStatus(500);
    }
}
