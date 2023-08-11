<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Database\Factories\UserFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    protected $with =
    [
        // 'outOfOfficeEvents', // $this->outOfOfficeEvents() method
        // 'meetingEvents', // $this->meetingEvents() method
        // 'meetingAppointments' // $this->meetingAppointments() method
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // relationship to OutOfOfficeEvent model ('out_of_office_events' table)
    public function outOfOfficeEvents()
    {
        return $this->hasMany(OutOfOfficeEvent::class,
                                'user_id_fk',
                                'id'
                            );
    }

    // relationship to MeetingEvent model ('meeting_events' table)
    public function meetingEvents()
    {
        return $this->hasMany(MeetingEvent::class,
                            'host_user_id_fk',
                            'id'
                        );
    }

    // relationship to MeetingAppointment model ('meeting_appointments' table)
    public function meetingAppointments()
    {
        return $this->belongsToMany(MeetingAppointment::class,
                                    'invitations',
                                    'guest_user_id_fk',
                                    'appointment_id_fk',
                                    'id',
                                    'id'
                                );
    }

    // explicitly specify what factory is associated with this model class
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding factory
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
