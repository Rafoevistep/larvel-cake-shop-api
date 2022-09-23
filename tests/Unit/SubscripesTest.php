<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscripesTest extends TestCase
{
    use WithFaker;

   public function test_user_subscribe_with_email()
   {
        $response = $this->withHeader('Accept', 'application/json')
        ->post('api/subscripe', [
            'email' => $this->faker->email
        ]);

        $response->assertStatus(200);

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
            ->get('api/admin/subscripe');


        $response->assertStatus(200);
    }
}
