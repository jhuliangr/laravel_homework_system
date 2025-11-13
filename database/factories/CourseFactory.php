<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'module_name' => $this->faker->randomElement([
                'Math',
                'Design',
            ]),
            'start_date' => $this->faker->dateTimeBetween('-1 year', '+6 months'),
        ];
    }
}
