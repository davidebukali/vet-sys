<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Treatment>
 */
class TreatmentTestFactory extends Factory
{
    /**
     * The name of the factory's model.
     *
     * @var string
     */
    protected $model = Treatment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'description' => fake()->sentence(),
            'notes' => fake()->text(),
            'price' => fake()->numberBetween(100, 1000)
        ];
    }
}
