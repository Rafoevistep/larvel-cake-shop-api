<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $authToken;
    public $authUser;

    public function loginUser()
    {
        $user = User::factory()->create();
        $body = [
            'email' => $user->email,
            'password' => 'password'
        ];
        $response =  $this->json('POST','/api/auth/login',$body,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $response = $response->json();
        $this->authToken = $response['token'];
        $this->authUser = $response['data'];

        return $response;

    }


    public function loginAdmin()
    {
        $body = [
            'email' => 'admin@gmail.com',
            'password' => '12345678'
        ];
        $response =  $this->json('POST','/api/auth/login',$body,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $response = $response->json();
        $this->authToken = $response['token'];
        $this->authUser = $response['data'];

        return $response;

    }

    public function loginSingleUser()
    {
        $body = [
            'email' => 'user@gmail.com',
            'password' => '12345678'
        ];
        $response =  $this->json('POST','/api/auth/login',$body,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $response = $response->json();
        $this->authToken = $response['token'];
        $this->authUser = $response['data'];

        return $response;

    }

}
