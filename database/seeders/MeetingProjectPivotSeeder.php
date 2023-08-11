<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingProjectPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentProjectsPerMeetingCount = 0;
        $allProjectsIdsArray = range(1, SeedConstants::PROJECT_COUNT);
        $finalProjectsIdsArray = [];
        $projectId = 0;

        // for every meeting, associate one or more projects
        for($meeting=0; $meeting<SeedConstants::MEETINGS_COUNT; $meeting++)
        {
            $currentProjectsPerMeetingCount = fake()->numberBetween(
                                                SeedConstants::MIN_PROJECTS_PER_MEETING,
                                                SeedConstants::MAX_PROJECTS_PER_MEETING
                                            );

            shuffle($allProjectsIdsArray);
            $finalProjectsIdsArray = array_slice($allProjectsIdsArray, 0, $currentProjectsPerMeetingCount);

            for($project=0; $project<$currentProjectsPerMeetingCount; $project++)
            {
                $projectId = $finalProjectsIdsArray[$project];

                DB::table('meetings_projects_pivot')->insert(
                    [
                        'meeting_id_fk' => $meeting + 1,
                        'project_id_fk' => $projectId
                    ]
                );
            }
        }
    }
}
