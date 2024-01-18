<?php

namespace Database\Factories;

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
            'username' => fake()->name(),
            'full_name' => fake()->name(),
            'password' => static::$password ??= Hash::make('password'),
            'phone_number' => fake()->randomNumber(),
            // 'user_group_id' => mt_rand(1,4),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    
}
