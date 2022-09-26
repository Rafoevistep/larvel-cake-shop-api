<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use WithFaker;

    const ABOUT_STRUCTURE = [
        "id",
        "name",
        "description",
        "created_at",
        "updated_at"
    ];

    const CONTACT_STRUCTURE = [

    ];

    public function test_create_about_page()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/about', [
                'name' => $this->faker->realTextBetween(10,20),
                'description' => $this->faker->realTextBetween(20,50),
            ])
            ->assertStatus(200)
            ->assertJsonStructure(self::ABOUT_STRUCTURE);
    }

    public function test_create_about_page_validation_empty_fields()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/about', [
                'name' => " ",
                'description' => "",
            ]);

        $response->assertStatus(422);

    }

    public function test_get_about_page()
    {
        $response = $this->get('api/about')
            ->assertStatus(200)
            ->assertJsonStructure(self::ABOUT_STRUCTURE);
    }

    public function test_create_contact_page()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/contact', [
                'address' => $this->faker->address,
                'phone' => $this->faker->phoneNumber,
                'email' => $this->faker->email,
            ])
            ->assertStatus(200)
            ->assertJsonStructure(self::CONTACT_STRUCTURE);
    }

    public function test_create_contact_page_validation()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/contact', [
                'address' => "",
                'phone' => "",
                'email' => ""
            ]);

        $response->assertStatus(422);

    }

    public function test_get_contact_page()
    {
        $response = $this->get('api/contact')
            ->assertStatus(200)
            ->assertJsonStructure(self::CONTACT_STRUCTURE);
    }
}
