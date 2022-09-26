<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::factory()->create();
        return [
            'name' => $this->faker->text(15),
            'description' => $this->faker->text(50),
            'category_id' => $category->id,
            'price' => $this->faker->numberBetween(100, 200),
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ];
    }
}
