<?php

namespace Database\Seeders;


use DateTime;
use DateInterval;

use App\Models\MeetingAppointment;
use App\Models\MeetingEvent;
use App\Models\OutOfOfficeEvent;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allUsersIdsArray = range(1, SeedConstants::USERS_COUNT);

        $currentYear = date('Y');
        $currentMonth = date('n');
        $startWeek = 4 * $currentMonth; // naive simplification to get current week (between 1 and 52)
        $currentDay = 1; // first day (Monday)
        $start = new DateTime();
        $end = new DateTime();

        $start->setTime(9, 0); // 9 AM
        $start->setISODate($currentYear, $startWeek, $currentDay);

        for($week=0; $week<SeedConstants::WEEKS_WITH_EVENTS; $week++)
        {
            for($day=0; $day<SeedConstants::DAYS_PER_WEEK_WITH_EVENTS; $day++)
            {
                shuffle($allUsersIdsArray);
                $currentDayUsers = array_slice($allUsersIdsArray, 0, SeedConstants::TOTAL_USERS_PER_DAY_WITH_EVENTS);
                
                $meetingUserId = 0;
                for($meetingUser=0; $meetingUser<SeedConstants::HOST_USERS_PER_DAY_WITH_MEETINGS; $meetingUser++)
                {
                    $meetingUserId = $currentDayUsers[$meetingUser];

                    MeetingEvent::factory()->create(
                        [
                            'host_user_id_fk' => $meetingUserId,
                            'location_id_fk' => fake()->numberBetween(1, SeedConstants::LOCATIONS_COUNT),
                            'title' => fake()->word(),
                            'description' => fake()->text(1024) // fake text of maximum 1024 characters
                        ]
                    );
                }

                $outOfOfficeUserId = 0;
                for($outOfOfficeUser=0;
                    $outOfOfficeUser<SeedConstants::USERS_PER_DAY_WITH_OUT_OF_OFFICE_EVENTS;
                    $outOfOfficeUser++)
                {
                    $outOfOfficeUserId = $currentDayUsers[SeedConstants::HOST_USERS_PER_DAY_WITH_MEETINGS + $outOfOfficeUser];

                    $start->setISODate($currentYear, $startWeek + $week, $currentDay + $day);
                    $start->setTime(0, 0); // 8 hours and zero minutes

                    $outOfOfficeDuration = DateInterval::createFromDateString("24 hours");
    
                    $end = new DateTime($start->format('Y-m-d H:i:s'));
                    $end->add($outOfOfficeDuration);


                    OutOfOfficeEvent::factory()->create(
                        [
                            'user_id_fk' => $outOfOfficeUserId,
                            'description' => fake()->text(1024), // fake text of maximum 1024 characters
                            'start' => $start->format('Y-m-d H:i:s'),
                            'end' => $end->format('Y-m-d H:i:s')
                        ]
                    );
                }
            }
        }
    }
}
