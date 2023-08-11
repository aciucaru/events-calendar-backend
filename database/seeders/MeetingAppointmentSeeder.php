<?php

namespace Database\Seeders;

use DateTime;
use DateInterval;

use App\Models\MeetingAppointment;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Seeder;

class MeetingAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        $startWeek = 4 * $currentMonth; // naive simplification to get current week (between 1 and 52)
        $currentDay = 1; // first day (Monday)
        $currentHour = 9; // 9 AM
        $currentMinutes = 0;
        $start = new DateTime();
        $end = new DateTime();
        $meetingDuration = DateInterval::createFromDateString('30 minutes');
        $durationInMinutes = 15;

        $start->setTime(9, 0); // 9 AM
        $start->setISODate($currentYear, $startWeek, $currentDay);

        $eventId = 1;
        for($week=0; $week<SeedConstants::WEEKS_WITH_EVENTS; $week++)
        {
            for($day=0; $day<SeedConstants::DAYS_PER_WEEK_WITH_EVENTS; $day++)
            {
                $start->setISODate($currentYear, $startWeek + $week, $currentDay + $day);

                for($meetingUser=0; $meetingUser<SeedConstants::HOST_USERS_PER_DAY_WITH_MEETINGS; $meetingUser++)
                {
                    $currentHour = fake()->numberBetween(9, 15); // 9:00 ... 15:00
                    $currentMinutes = 15 * fake()->numberBetween(0, 3); // 0, 15, 30 or 45 minutes
                    $start->setTime($currentHour, $currentMinutes);

                    $durationInMinutes = 15 * fake()->numberBetween(1, 4);
                    $meetingDuration = DateInterval::createFromDateString("{$durationInMinutes} minutes");

                    $end = new DateTime($start->format('Y-m-d H:i:s'));
                    $end->add($meetingDuration);

                    MeetingAppointment::factory()->create(
                        [
                            'meeting_id_fk' => $eventId,
                            'active' => 1, // TRUE (the active appointment)
                            'start' => $start->format('Y-m-d H:i:s'),
                            'end' => $end->format('Y-m-d H:i:s')
                        ]
                    );

                    $eventId++;
                }
            }
        }
    }
}

