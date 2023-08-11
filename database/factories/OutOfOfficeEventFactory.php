<?php

namespace Database\Factories;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\OutOfOfficeEvent;
use Database\Seeders\SeedConstants;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class OutOfOfficeEventFactory extends Factory
{
    // define the model class for which this factory creates elements,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding model class
    protected $model = OutOfOfficeEvent::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // generate a date from the current month
        $start = fake()->dateTimeInInterval('0 week', '+4 week');
        $duration = DateInterval::createFromDateString('1 hour');
        $end = new DateTime($start->format('Y-m-d H:i:s'));
        $end->add($duration);

        return [
            'user_id_fk' => fake()->numberBetween(1, SeedConstants::USERS_COUNT),
            'description' => fake()->text(1024), // fake text of maximum 1024 characters
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s')
        ];
    }
}
