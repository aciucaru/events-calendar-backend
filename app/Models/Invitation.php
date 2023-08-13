<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Database\Factories\InvitationFactory;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'appointment_id_fk',
        'guest_user_id_fk',
        'guest_answer'
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

    /* this time, use special Laravel '$with' model class property to automatically load
    the specified nested resource from the database; this method still requires to
    explicitly specify the column to which the corresponding foreign key points to */
    protected $with =
    [
        // 'meetingAppointment', // $this->meetingAppointment() method
        'guestUser' // $this->gustUser() method;  THIS MUST REMAIN!!, it's sent to the fron-end
    ];

    public function meetingAppointment()
    {
        return $this->belongsTo(
            MeetingAppointment::class, // model class of containing resource
            'appointment_id_fk', // foreign key of this table used to point to 'meeting_appointments' table
            'id' // key of other table ('meeting_appointments') to which the above foreign key points to
        );
    }

    public function guestUser()
    {
        return $this->belongsTo(
            User::class, // model class of containing resource
            'guest_user_id_fk', // foreign key of this table used to point to 'users' table
            'id' // key of other table ('users') to which the above foreign key points to
        );                      
    }

    // explicitly specify what factory is associated with this model class,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding factory
    protected static function newFactory(): Factory
    {
        return InvitationFactory::new();
    }
}
