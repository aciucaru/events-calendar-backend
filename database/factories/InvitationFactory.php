<?php

namespace Database\Factories;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Seeders\SeedConstants;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    // define the model class for which this factory creates elements,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding model class
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appointment_id_fk' => fake()->numberBetween(1, SeedConstants::APPOINTMENTS_COUNT),
            'guest_user_id_fk' => fake()->numberBetween(1, SeedConstants::USERS_COUNT),
            'guest_answer' => fake()->randomElement(['NO_ANSWER', 'YES', 'NO', 'MAYBE'])
        ];
    }
}
