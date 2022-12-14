<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class StripePaymentTest extends TestCase
{

    public function test_single_product_checkoutStripe()
    {
        $this->loginUser();

        $product = Product::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post("api/auth/stripe/{$product->id}", [
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
        $this->loginUser();

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
        $this->loginUser();

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
