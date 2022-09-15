<?php

namespace Tests\Unit;

use Tests\TestCase;

class EnquiriesTest extends TestCase
{
    public function test_user_write_enquiry()
    {
        $user = $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/enquary', [
                'name' => "user",
                'email' => 'user@gmail.com',
                'message' => 'Good Cakes Owr Shop'
            ]);

        $response->assertStatus(200);
    }

    public function test_user_write_enquiry_validation()
    {
        $user = $this->loginUser();

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
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->get('api/admin/enquary');

        $response->assertStatus(200);
    }
}
