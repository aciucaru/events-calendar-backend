<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Database\Factories\MeetingEventFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MeetingEvent extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'host_user_id_fk',
        'location_id_fk',
        'title',
        'description',
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
        // 'hostUser', // $this->hostUser() relationship method
        // 'location', // $this->location() relationship method
        // 'meetingAppointments', // $this->meetingAppointments() relationship method
        // 'projects' // $this->projects() relationship method
    ];


    // relationship to User model ('user' table)
    public function hostUser()
    {
        /* must explicitly specify name of column (in this case 'id') to which the foreign key points to,
        otherwise loading of nested resource does not work in this case */
        return $this->belongsTo(User::class,
                                'host_user_id_fk',
                                'id'
                            );
    }

    // relationship to Location model ('locations' table)
    public function location()
    {
        /* must explicitly specify name of column (in this case 'id') to which the foreign key points to,
        otherwise loading of nested resource does not work in this case */
        return $this->belongsTo(Location::class,
                                    'location_id_fk',
                                    'id'
                                );
    }

    // relationship to MeetingAppointment model ('meeting_appointments' table)
    public function meetingAppointments()
    {
        return $this->hasMany(
                        MeetingAppointment::class, // model class of nested resource
                        'meeting_id_fk', // foreign key of table that point to this model ('meeting_appointments' table)
                        'id' // key of this table, to wihich the above foreign key points to
                    );
    }    

    // relationship to Project model ('projects' table)
    public function projects()
    {
        /* Laravel uses naming conventions to figure out corresponding tables and columns names,
        but if the names of tables or key do not fit with Laravel conventions, the loading of nested
        resources won't work, so exact names are specified here */
        return $this->belongsToMany(Project::class, // the model class coresponding to the related resource
                                    'meetings_projects_pivot', // name of pivot table that makes the connection
                                    'meeting_id_fk', // pivot table key pointing to this resource (MeetingEvent)
                                    'project_id_fk', // pivot table key pointing to the other resource (Project)
                                    'id',
                                    'id'
                                );
    }


    // explicitly specify what factory is associated with this model class
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding factory
    protected static function newFactory(): Factory
    {
        return MeetingEventFactory::new();
    }
}
