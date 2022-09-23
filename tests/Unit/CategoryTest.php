<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->assertTrue(TRUE);

    }

    public function test_category()
    {
        Category::factory()->create();

        $response = $this->get('/api/categoty');

        $response->status(200);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function test_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->get("/api/categoty/$category->id");

        $response->status(200);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function test_category_not_found()
    {
        $response = $this->get('/api/categoty/999');

        $response->status(404);

        $this->assertEquals(404, $response->getStatusCode());

    }

    public function test_create_category()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('/api/admin/categoty', [
                'name' => "Birthday Cake",
            ]);

        $response->assertStatus(200);

    }


    public function test_update_category()
    {
        $this->loginAdmin();

        $category = Category::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("/api/admin/categoty/$category->id", [
                'name' =>  $this->faker->text(15) . ' Updated',
            ]);
        $response->assertStatus(200);
    }

    public function test_update_category_validation()
    {
        $this->loginAdmin();

        $category = Category::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("/api/admin/categoty/$category->id", [
                'name' => "",
            ]);

        $response->assertStatus(422);

    }

    public function test_delete_category()
    {
        $this->loginAdmin();

        $category = Category::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->delete("/api/admin/categoty/$category->id");

        $response->assertStatus(200);

    }

}
