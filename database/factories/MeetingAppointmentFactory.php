<?php

namespace Database\Factories;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\MeetingAppointment;
use Database\Seeders\SeedConstants;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class MeetingAppointmentFactory extends Factory
{
    // define the model class for which this factory creates elements,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding model class
    protected $model = MeetingAppointment::class;


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
            'meeting_id_fk' => fake()->unique()->numberBetween(1, SeedConstants::MEETINGS_COUNT),
            'active' => 1, // true
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s')
        ];
    }
}
