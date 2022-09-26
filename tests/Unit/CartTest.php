<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Product;
use Tests\TestCase;

class CartTest extends TestCase
{
    const CART_STRUCTURE = [
        "id",
        "user_id",
        "product_id",
        "name",
        "image",
        "price",
        "description",
        "qty",
        "updated_at",
        "created_at",
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->assertTrue(TRUE);
    }

     function test_add_to_cart()
     {
         $user = $this->loginUser();

        $product = Product::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post("api/auth/cart/{$product->id}", [
                'qty' => 2
            ])
            ->assertStatus(200)
            ->assertJsonStructure(
               self::CART_STRUCTURE
            );
    }

    public function test_delete_from_cart()
    {
        $this->loginUser();

        $product = Product::factory()->create();

        $cart = Cart::create([
            'user_id' => $this->authUser['id'],
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'price' => $product->price,
            'description' => $product->description,
            'qty' => 2,
        ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->delete("api/auth/cart/{$cart->id}");

        $response->assertStatus(200);

    }

    public function test_get_cart()
    {
        $this->loginUser();

        $product = Product::factory()->create();

        $AddToCart = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post("api/auth/cart/{$product->id}", [
                'qty' => 2
            ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->get('api/auth/cart')
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'total',
                'cart' => [
                    '*' => self::CART_STRUCTURE
                ]
            ]);
    }

}
