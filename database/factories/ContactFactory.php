<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'title' => $this->faker->jobTitle,
            'company' => $this->faker->company,
            'phone' => $this->faker->PhoneNumber,
            'email' => $this->faker->unique()->safeEmail(),
            'unsub_link' => base64_encode($this->faker->unique()->safeEmail()),
            'source' => $this->faker->randomElement([0, 1]),
            'status' => $this->faker->randomElement([0, 1]),
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'linkedIn_profile' => $this->faker->url,
            'reached_count' => $this->faker->numberBetween(0, 100),
            'reached_platform' => $this->faker->name,
            'lead_status_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'industry_id' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
