<?php

namespace Database\Seeders;

class SeedConstants
{
    public const USERS_COUNT = 50;
    public const LOCATIONS_COUNT = 20;

    public const WEEKS_WITH_EVENTS = 8;
    public const DAYS_PER_WEEK_WITH_EVENTS = 5;

    public const USERS_PER_DAY_WITH_OUT_OF_OFFICE_EVENTS = 4;
    public const HOST_USERS_PER_DAY_WITH_MEETINGS = 10;
    public const TOTAL_USERS_PER_DAY_WITH_EVENTS = self::USERS_PER_DAY_WITH_OUT_OF_OFFICE_EVENTS
                                                    + self::HOST_USERS_PER_DAY_WITH_MEETINGS;

    public const OUT_OF_OFFICE_EVENTS_COUNT = self::WEEKS_WITH_EVENTS
                                                * self::DAYS_PER_WEEK_WITH_EVENTS
                                                * self::USERS_PER_DAY_WITH_OUT_OF_OFFICE_EVENTS;
    public const MEETINGS_COUNT = self::WEEKS_WITH_EVENTS
                                * self::DAYS_PER_WEEK_WITH_EVENTS
                                * self::HOST_USERS_PER_DAY_WITH_MEETINGS;

    public const APPOINTMENTS_COUNT = self::MEETINGS_COUNT;
    
    public const MIN_INVITATIONS_PER_APPOINTMENT = 4;
    public const MAX_INVITATIONS_PER_APPOINTMENT = 8;
    
    public const PROJECT_COUNT = 20;

    public const MIN_PROJECTS_PER_MEETING = 1;
    public const MAX_PROJECTS_PER_MEETING = 5;
}