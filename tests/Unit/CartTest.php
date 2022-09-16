<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->assertTrue(TRUE);
    }

     function test_add_to_cart()
        {
        $this->loginSingleUser();

        $product = Product::create([
            'name' => "New Product",
            'description' => "This is a product",
            'category_id' => '1',
            'price' => '30',
            'image' => UploadedFile::fake()->image('photo.jpg'),
            'qty' => 10
        ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post("api/auth/cart/{$product->id}", [
                'qty' => 2
            ]);

        $response->assertStatus(200);

    }

    public function test_delete_from_cart()
    {
       $user = $this->loginSingleUser();

        $product = Product::create([
            'name' => "New Product",
            'description' => "This is a product",
            'category_id' => '1',
            'price' => '30',
            'image' => UploadedFile::fake()->image('photo.jpg'),
            'qty' => 10
        ]);

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

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->get('api/auth/cart');

        $response->assertStatus(200);

    }
}
