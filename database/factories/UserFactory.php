<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    protected $model = \App\Models\User::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'remember_token' => $this->faker->randomNumber(8),
            'verify_email' => $this->faker->boolean(50),
            'is_active' => $this->faker->boolean(90),
            'is_delete' => $this->faker->boolean(10),
            'group_role' => $this->faker->randomElement(['admin', 'user', 'guest']),
            'last_login_at' => $this->faker->optional()->dateTime(),
            'last_login_ip' => $this->faker->optional()->ipv4(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
