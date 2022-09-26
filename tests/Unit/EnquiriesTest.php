<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnquiriesTest extends TestCase
{
    const ENQUIRY_STRUCTURE = [
        "id" ,
        "user_id",
        "name",
        "email",
        "message",
        "updated_at",
        "created_at",
    ];
    use  WithFaker;
    public function test_user_write_enquiry()
    {
         $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/enquary', [
                'name' => $this->faker->firstName,
                'email' => $this->faker->email,
                'message' => $this->faker->realTextBetween(11,30)
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => self::ENQUIRY_STRUCTURE
            ]);
    }

    public function test_user_write_enquiry_validation()
    {
        $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/enquary', [
                'name' => "user",
                'email' => '',
                'message' => 'Good Cakes Owr Shop'
            ]);

        $response->assertStatus(422);
    }

    public function test_admin_get_enquiries()
    {
        $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->get('api/admin/enquary')
            ->assertStatus(200)
            ->assertJsonStructure([
                self::ENQUIRY_STRUCTURE
            ]);
    }
}
