<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Database\Factories\LocationFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'name',
        'description'
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at'
    ];

    protected $with =
    [
        // 'meetingEvents' // $this->meetingEvents() relationship method
    ];

    // relationship to MeetingEvent model ('meeting_events' table)
    public function meetingEvents()
    {
        return $this->hasMany(MeetingEvent::class);
    }

    // explicitly specify what factory is associated with this model class,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding factory
    protected static function newFactory(): Factory
    {
        return LocationFactory::new();
    }
}
