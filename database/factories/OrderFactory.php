<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User:: factory()->create();
        $product = Product::factory()->create();
        return [
            'order_number' => Str::uuid() ,
            'user_id' => $user->id,
            'flat' => $this->faker->city,
            'street_name' => $this->faker->streetName,
            'area' => $this->faker->streetName,
            'landmark' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'payment_method' => 'cash_on_delivery',
        ];

    }
}
