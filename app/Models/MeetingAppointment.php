<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Database\Factories\MeetingAppointmentFactory;

class MeetingAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id_fk',
        'active',
        'start',
        'end'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden =
    [
        'created_at',
        'updated_at'
    ];

    protected $with =
    [
        'meetingEvent', // $this->meetingEvent() relationship method
        // 'invitations' // $this->invitations() relationship method
    ];

    // relationship to MeetingEvent model ('meeting_events' table)
    public function meetingEvent()
    {
        return $this->belongsTo(
                MeetingEvent::class, // model class of containing resource
                'meeting_id_fk', // foreign key of this table used to point to 'meeting_events' table
                'id' // key of other table ('meeting_events' table) to which the above foreign key points to
            );
    }

    // relationship to Invitation model ('invitations' table)
    public function invitations()
    {
        return $this->hasMany(
            Invitation::class, // model class of contained resource
            'appointment_id_fk', // foreign key from other table (meeting_appointments) that point to 'meeting_appointments'
            'id' // local id of this table (meeting_appointments) to which the above foreign key points to
        );
    }

    // explicitly specify what factory is associated with this model class,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding factory
    protected static function newFactory(): Factory
    {
        return MeetingAppointmentFactory::new();
    }
}
