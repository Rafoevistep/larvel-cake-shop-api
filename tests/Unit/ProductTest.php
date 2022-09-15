<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ProductTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->assertTrue(TRUE);
    }

    public function test_products()
    {
        $response = $this->get('api/products');

        $response->status(200);


    }

    public function test_single_products()
    {
        $response = $this->get('api/products/3');

        $response->status(200);


    }

    public function test_products_not_found()
    {
        $response = $this->get('api/products/999');

        $response->status(404);


    }

    public function test_create_product()
    {
        $user =  $this->loginAdmin();

//      dd($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post('api/admin/products-create', [
                'name' => "New Product",
                'description' => "This is a product",
                'category_id' => '1',
                'price' => '30',
                'image' => UploadedFile::fake()->image('photo.jpg'),
                'qty' => 10
                ]);

        $response->assertStatus(200);

    }

    public function test_create_product_validation()
    {
        $user =  $this->loginAdmin();

//      dd($user);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post('api/admin/products-create', [
                'name' => "",
                'description' => "",
                'category_id' => '1',
                'price' => '30',
                'image' => UploadedFile::fake()->image('photo.jpg'),
                'qty' => 10
            ]);

        $response->assertStatus(422);

    }

    public function test_update_product(){

        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->post('api/admin/products-create', [
                'name' => "New Product Update",
                'description' => "This is a product",
                'category_id' => '1',
                'price' => '40',
                'image' => UploadedFile::fake()->image('photo.jpg'),
                'qty' => 10
            ]);

        $response->assertStatus(200);
    }

    public function test_delete_product(){

        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept' , 'application/json')
            ->delete('api/admin/products/268');

        $response->assertStatus(200);
    }

    public function test_show_available_product(){

        $response = $this->get('api/products/available');

        $response->assertStatus(200);

    }

    public function test_search_product(){

        $response = $this->get('/api/products/search/cake');

        $response->assertStatus(200);

    }

    public function test_search_product_not_found(){

        $response = $this->get('/api/products/search/asdjuhaduhasd');

        $response->assertStatus(404);

    }
}
