<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = \App\Models\Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'tel_num' => $this->faker->regexify('0[0-9]{9}'),
            'address' => $this->faker->city() . ', Việt Nam',
            'is_active' => $this->faker->boolean(90),
            'is_delete' => $this->faker->boolean(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
