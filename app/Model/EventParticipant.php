<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $table = "event_participant";

    protected $fillable = ['​user_id', 'event_id', 'joined_date'];

    public $timestamps = false;
}
