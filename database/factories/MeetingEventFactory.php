<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\MeetingEvent;
use Database\Seeders\SeedConstants;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class MeetingEventFactory extends Factory
{
    // define the model class for which this factory creates elements,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding model class
    protected $model = MeetingEvent::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'host_user_id_fk' => fake()->numberBetween(1, SeedConstants::USERS_COUNT),
            'location_id_fk' => fake()->numberBetween(1, SeedConstants::LOCATIONS_COUNT),
            'title' => fake()->word(),
            'description' => fake()->text(1024), // fake text of maximum 1024 characters
        ];
    }
}
