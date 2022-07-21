<?php

namespace Database\Factories;

use App\Models\DecisionMakersEmails;
use Illuminate\Database\Eloquent\Factories\Factory;

class DecisionMakersEmailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DecisionMakersEmails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'decision_maker_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
