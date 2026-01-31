<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              =>  fake()->name(),
            'last_name'         =>  fake()->lastName(),
            'email'             =>  fake()->unique()->safeEmail(),
            'email_verified_at' =>  now(),
            'password'          =>  static::$password ??= Hash::make('password'),
            'remember_token'    =>  Str::random(10),
            'phone'             =>  fake()->optional()->phoneNumber(),
            'file_id'           =>  null, // Will be set later by withProfilePhoto()
            'status'            =>  Status::ACTIVE,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Attach a profile photo to the user.
     */
    public function withProfilePhoto(): static
    {
        return $this->afterCreating(function (User $user) {
            $file = File::factory()->withPath('user')->create();

            $user->update(['file_id' => $file->id]);
        });
    }
}
