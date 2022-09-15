<?php

namespace Tests\Unit;

use Tests\TestCase;

class SubscripesTest extends TestCase
{
   public function test_user_subscribe_with_email()
   {
        $user =  $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/subscripe', [
                'email' => "user5@gmail.com"
            ]);

        $response->assertStatus(200);
   }

    public function test_get_subscribe_email_empty_validation()
    {
        $user =  $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/subscripe', [
                'email' => ""
            ]);

        $response->assertStatus(422);
    }

    public function test_show_subscribe_emails()
    {
        $user =  $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->get('api/admin/subscripe');

        $response->assertStatus(200);
    }
}
