<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use \Tests\CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->assertTrue(TRUE);
    }

    public function test_login()
    {
        $this->loginUser();
        $body = [
            'email' => 'user@gmail.com',
            'password' => '12345678'
        ];
        $response = $this->json('POST', '/api/auth/login', $body, ['Accept' => 'application/json'])
            ->assertJsonStructure(['token']);

        $response->assertStatus(200);
    }


    public function test_authenticated_user_can_logout()
    {
        $this->loginUser();

        $response = $this->get('api/auth/logout', [
            'Authorization' => "Bearer $this->authToken"
        ]);

        $response->assertStatus(200);
    }

    public function test_show_validation_error_when_both_fields_empty()
    {

        $response = $this->post('/api/auth/login', [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);

    }

    public function test_show_validation()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'user@gmail.inbox',
            'password' => '12345678'
        ]);

        $response->assertJson([
            "message" => "Incorrect credentials"
        ]);

    }

    public function test_authenticated_user_can_get_user_details()
    {
        $this->loginUser();

        $response = $this->get('/api/auth/user', [
            'Authorization' => "Bearer $this->authToken"
        ]);

        $data = $response->json();

        $this->assertArrayHasKey('data', $data);
        $this->assertEquals($data['data']['id'], $this->authUser['id']);
        $this->assertEquals($data['data']['first_name'], $this->authUser['first_name']);
        $this->assertEquals($data['data']['last_name'], $this->authUser['last_name']);
        $this->assertEquals($data['data']['phone'], $this->authUser['phone']);
        $this->assertEquals($data['data']['is_admin'], $this->authUser['is_admin']);
        $this->assertEquals($data['data']['email'], $this->authUser['email']);
        $this->assertEquals($data['data']['email_verified_at'], $this->authUser['email_verified_at']);

        $response->assertStatus(200);
    }

    public function test_user_can_update_own_profile()
    {
        $user = $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->post('api/profile/update-profile', [
                'first_name' => 'FirstnameUpdate',
                'last_name' => 'LastnameUpdate',
                'phone' => '13245864'
            ]);

        $response->assertStatus(200);
    }

    public function test_user_can_update_own_password()
    {
        $user = $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->post('api/profile/change-password', [
                'old_password' => 'password',
                'password' => '123456789',
                'confirm_password' => '123456789'
            ]);

        $response->assertStatus(200);
    }

    public function test_user_can_update_own_password_validate_wrong_password()
    {
        $user = $this->loginUser();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->post('api/profile/change-password', [
                'old_password' => 'passwordWrong',
                'password' => '123456789',
                'confirm_password' => '123456789'
            ]);

        $response->assertStatus(400);
    }
}






