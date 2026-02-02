<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => strtoupper(fake()->company()),
            'trade_name' => strtoupper(fake()->company()),
            'document' => random_int(10000000000, 99999999999),
            'email' => fake()->optional()->companyEmail(),
            'phone_number' => fake()->optional()->phoneNumber(),
            'file_id' => null,
        ];
    }
}
