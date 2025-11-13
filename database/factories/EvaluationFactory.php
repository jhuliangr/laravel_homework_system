<?php

namespace Database\Factories;

use App\Models\Homework;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => fake() -> numberBetween(1,5),
            'homework_id' => Homework::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
