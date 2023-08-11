<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Database\Factories\OutOfOfficeEventFactory;

class OutOfOfficeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_fk',
        'description',
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

    /* this time, use special Laravel '$with' model class property to automatically load
    the specified nested resource from the database; this method still requires to
    explicitly specify the column to which the corresponding foreign key points to */
    protected $with =
    [
        'user' // $this->gustUser() method
    ];

    // relationship to User model ('users' table)
    public function user()
    {
        // must specify 'id' column from 'users' table used as foreign key in 'out_of_office_events_table'
        // otherwise Laravel eager loading will not work
        return $this->belongsTo(User::class,
                                'user_id_fk',
                                'id'
                            );
    }


    // explicitly specify what factory is associated with this model class
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding factory
    protected static function newFactory(): Factory
    {
        return OutOfOfficeEventFactory::new();
    }
}
