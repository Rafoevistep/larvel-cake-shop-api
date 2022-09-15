<?php

namespace Tests\Unit;

use Tests\TestCase;

class PagesTest extends TestCase
{

    public function test_create_about_page()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/about', [
                'name' => "Who are we? ",
                'description' => "The Cake Shop is a Jordanian Brand that started as a small family business.The owners are Dr. Iyad Sultan and Dr. Sereen Sharabati, supported by a staff of 80 employees.Although not small any more, the business tries to keep the family atmosphere where we care not only about our company, our products and our staff, but also we consider each customer a member in this family of cake shoppers.Our mission is to make people happy. Making delicious cakes, having relaxing chairs in a smoking free environment and keeping our prices reasonable are all different ways to achieve this goal.",
            ]);

        $response->assertStatus(200);

    }

    public function test_create_about_page_validation_empty_fields()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/about', [
                'name' => " ",
                'description' => "",
            ]);

        $response->assertStatus(422);

    }

    public function test_get_about_page()
    {
        $response = $this->get('api/about');

        $response->assertStatus(200);

    }

    public function test_create_contact_page()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/contact', [
                'address' => "Lorem Ipsum has been the industry's standard ",
                'phone' => "+9627751553001",
                'email' => "cakeshop@mail.ru"
            ]);

        $response->assertStatus(200);

    }

    public function test_create_contact_page_validation()
    {
        $user = $this->loginAdmin();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $this->authToken)
            ->withHeader('Accept', 'application/json')
            ->post('api/admin/contact', [
                'address' => "",
                'phone' => "",
                'email' => ""
            ]);

        $response->assertStatus(422);

    }

    public function test_get_contact_page()
    {
        $response = $this->get('api/contact');

        $response->assertStatus(200);

    }

}
