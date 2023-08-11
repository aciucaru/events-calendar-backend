<?php

namespace Database\Seeders;

use App\Models\Invitation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentAppointmentInvitationsCount = 0;
        $allUsersIdsArray = range(1, SeedConstants::USERS_COUNT);
        $guestUsersIdsArray = [];
        $currentGuestUserId = 0;

        for($appointment=0; $appointment<SeedConstants::APPOINTMENTS_COUNT; $appointment++)
        {
            $currentAppointmentInvitationsCount =
                    fake()->numberBetween(SeedConstants::MIN_INVITATIONS_PER_APPOINTMENT,
                                            SeedConstants::MAX_INVITATIONS_PER_APPOINTMENT);

            shuffle($allUsersIdsArray);
            $guestUsersIdsArray = array_slice($allUsersIdsArray, 0, $currentAppointmentInvitationsCount);

            for($invitation=0; $invitation<$currentAppointmentInvitationsCount; $invitation++)
            {
                $currentGuestUserId = $guestUsersIdsArray[$invitation];

                Invitation::factory()->create(
                    [
                        'appointment_id_fk' => $appointment + 1,
                        'guest_user_id_fk' => $currentGuestUserId
                    ]
                );
            }
        }
    }
}
