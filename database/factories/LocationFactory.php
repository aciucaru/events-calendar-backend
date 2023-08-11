<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Location;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    // define the model class for which this factory creates elements,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding model class
    protected $model = Location::class;
    

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $room = 'Room ' . 
                fake()->unique()->randomNumber(2, true); // a random number of 2 digits

        return [
            'name' => $room,
            'description' => fake()->text(250) // fake text of maximum 250 characters
        ];
    }
}
