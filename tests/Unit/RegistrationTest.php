<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;


class RegistrationTest extends TestCase
{
    use WithFaker;


    public function test_Registration()
    {

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/register', [
                'last_name' => $this->faker->lastName(),
                'first_name' => $this->faker->firstName(),
                'email' => $this->faker->email(),
                'password' => '12345678' ,
                'confirm_password' => '12345678',
                'phone' => $this->faker->buildingNumber(),
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                 'data' => [
                    'id',
                    "first_name",
                    "last_name",
                    "email",
                    "phone",
                    "updated_at",
                    "created_at",
                 ]
            ]);
    }


    public function test_Registration_error_empty_filed()
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/register', [
                'last_name' => '',
                'first_name' => '',
                'email' => '',
                'password' => '' ,
                'confirm_password' => '',
                'phone' => '',
            ]);

        $response->assertStatus(422);
    }

    public function test_Registration_error_taken_email()
    {

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/register', [
                'last_name' => $this->faker->lastName(),
                'first_name' => $this->faker->firstName(),
                'email' => 'user@gmail.com',
                'password' => '12345678' ,
                'confirm_password' => '12345678',
                'phone' => $this->faker->buildingNumber(),
            ]);

        $response->assertStatus(422);
    }

    public function test_Registration_error_password_must_match()
    {

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('api/auth/register', [
                'last_name' => $this->faker->lastName(),
                'first_name' => $this->faker->firstName(),
                'email' => $this->faker->email(),
                'password' => '123456789' ,
                'confirm_password' => '12345678',
                'phone' => $this->faker->buildingNumber(),
            ]);

        $response->assertStatus(422);
    }

}
