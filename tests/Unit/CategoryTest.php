<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use WithFaker;

    const CATEGORY_STRUCTURE = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->assertTrue(TRUE);

    }

    public function test_category()
    {
        Category::factory()->create();

        $response = $this->get('/api/categoty')
            ->assertStatus(200)
            ->assertJsonStructure([
                self::CATEGORY_STRUCTURE,
            ]);
    }

    public function test_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->get("/api/categoty/$category->id")
            ->assertStatus(200)
            ->assertJsonStructure([
                'categoty' => self::CATEGORY_STRUCTURE,
            ]);
    }

    public function test_category_not_found()
    {
        $response = $this->get('/api/categoty/9999');

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
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'category' => self::CATEGORY_STRUCTURE,
            ]);
    }

    public function test_update_category()
    {
        $this->loginAdmin();

        $category = Category::factory()->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put("/api/admin/categoty/$category->id", [
                'name' => $this->faker->text(15) . ' Updated',
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                self::CATEGORY_STRUCTURE,
            ]);
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
