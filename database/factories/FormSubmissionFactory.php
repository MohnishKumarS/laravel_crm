<?php

namespace Database\Factories;

use App\Models\Formsubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Formsubmission>
 */
class FormSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = FormSubmission::class;
    public function definition(): array
    {
        return [
            'form_id' => 2,
            'data' => [
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'phone' => fake()->numerify('98########'),
            ],
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
