<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExpenseCategory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseCategoryDetail>
 */
class ExpenseCategoryDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'category_id' => ExpenseCategory::factory(),
            'user_id' => User::factory(),
            'category_detail' => fake()->word,
            'amount' => fake()->randomFloat(2, 0, 10000),
            'date' => fake()->date,
            'is_investment' => fake()->boolean,
            'is_consumption' => fake()->boolean,
            'is_waste' => fake()->boolean,
        ];
    }
}
