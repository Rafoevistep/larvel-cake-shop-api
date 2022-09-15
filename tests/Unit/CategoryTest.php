<?php

namespace Tests\Unit;

use Tests\TestCase;

class CategoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->assertTrue(TRUE);
    }

    public function test_category()
    {
        $response = $this->get('/api/categoty');

        $response->status(200);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function test_single_category()
    {
        $response = $this->get('/api/categoty/1');

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
        $user = $this->loginAdmin();

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
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('/api/admin/categoty/3', [
                'name' => "Birthday Cake Update",
            ]);
        $response->assertStatus(200);
    }

    public function test_update_category_validation()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->put('/api/admin/categoty/3', [
                'name' => "",
            ]);

        $response->assertStatus(422);

    }

    public function test_delete_category()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->delete('/api/admin/categoty/30');

        $response->assertStatus(200);

    }

}
