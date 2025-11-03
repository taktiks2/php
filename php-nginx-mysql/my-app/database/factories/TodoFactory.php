<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),           // ランダムな文章
            'description' => fake()->paragraph(),     // ランダムな段落
            'completed' => fake()->boolean(20),       // 20%の確率でtrue
        ];
    }
}
