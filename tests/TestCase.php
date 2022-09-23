<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

//
//    /**
//     * Creates the application.
//     *
//     * @return \Illuminate\Foundation\Application
//     */
//    public function createApplication()
//    {
//        $app = require __DIR__.'/../bootstrap/app.php';
//
//        $app->make(Kernel::class)->bootstrap();
//
//        $this->clearCache(); // NEW LINE -- Testing doesn't work properly with cached stuff.
//
//        return $app;
//    }
//
//    /**
//     * Clears Laravel Cache.
//     */
//    protected function clearCache()
//    {
//        $commands = ['clear-compiled', 'cache:clear', 'optimize', 'view:clear', 'config:clear', 'route:clear','config:cache'];
//        foreach ($commands as $command) {
//            \Illuminate\Support\Facades\Artisan::call($command);
//        }
//    }


    public $authToken;
    public $authUser;


    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

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
