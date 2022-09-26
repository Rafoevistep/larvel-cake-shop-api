<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Queue\Capsule\Manager;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Capsule\Manager as DB;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public $authToken;
    public $authUser;


    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        Config::set('testing', true);
    }

    public function loginAdmin()
    {
        $body = [
            'email' => 'admin@gmail.com',
            'password' => '12345678'
        ];

        $response = $this->json('POST', '/api/auth/login', $body, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $response = $response->json();
        $this->authToken = $response['token'];
        $this->authUser = $response['data'];

        return $response;

    }

    public function loginUser()
    {
        $user = User::factory()->create();
        $body = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->json('POST', '/api/auth/login', $body, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $response = $response->json();
        $this->authToken = $response['token'];
        $this->authUser = $response['data'];

        return $response;

    }


}
