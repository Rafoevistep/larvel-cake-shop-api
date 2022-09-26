<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscripesTest extends TestCase
{
    use WithFaker;

    const SUBSCRIBE_STRUCTURE = [
        "id",
        "email",
        "updated_at",
        "created_at",
    ];

   public function test_user_subscribe_with_email()
   {
        $response = $this->withHeader('Accept', 'application/json')
        ->post('api/subscripe', [
            'email' => $this->faker->email
        ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'info' => self::SUBSCRIBE_STRUCTURE
        ]);

   }

    public function test_get_subscribe_email_empty_validation()
    {

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('api/subscripe', [
                'email' => ""
            ]);

        $response->assertStatus(422);
    }

    public function test_show_subscribe_emails()
    {
       $admin = $this->loginAdmin();


        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->get('api/admin/subscripe')
            ->assertStatus(200)
            ->assertJsonStructure([
                    self::SUBSCRIBE_STRUCTURE
            ]);

    }
}
