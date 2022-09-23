<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ProductTest extends TestCase
{
    use WithFaker;

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
        $product = Product::factory()->create();

        $response = $this->get("api/products/$product->id");

        $response->status(200);


    }

    public function test_products_not_found()
    {
        $response = $this->get('api/products/999');

        $response->status(404);


    }

    public function test_create_product()
    {
        $user = $this->loginAdmin();

        $category = Category::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/products-create', [
                'name' => $this->faker->text(15),
                'description' => $this->faker->text(50),
                'category_id' => $category->id,
                'price' => $this->faker->numberBetween(100, 200),
                'image' => UploadedFile::fake()->image('photo.jpg'),
                'qty' => $this->faker->numberBetween(10, 20),
            ]);

        $response->assertStatus(200);

    }

    public function test_create_product_validation()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
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

    public function test_update_product()
    {
        $user = $this->loginAdmin();
        $category = Category::factory()->create();
        $product = Product::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post("api/admin/product-update/$product->id", [
                '_method' => "PUT",
                'name' => $this->faker->text(15) . "updated",
                'description' => $this->faker->text(50),
                'category_id' => $category->id,
                'price' => $this->faker->numberBetween(100, 200),
                'image' => UploadedFile::fake()->image('photo.jpg'),
                'qty' => $this->faker->numberBetween(10, 20),
            ]);

        $response->assertStatus(200);
    }


    public function test_delete_product()
    {

        $user = $this->loginAdmin();

        $product = Product::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->delete("api/admin/products/{$product->id}");

        $response->assertStatus(200);
    }

    public function test_show_available_product()
    {
        $product = Product::factory()->create();

        $response = $this->get("api/products/$product->id");

        $response->assertStatus(200);

    }

    public function test_search_product()
    {
        $product = Product::factory()->create();

        $response = $this->get("/api/products/search/$product->name");

        $response->assertStatus(200);

    }

    public function test_search_product_not_found()
    {
        $response = $this->get('/api/products/search/asdjuhaduhasd');

        $response->assertStatus(404);
    }
}
