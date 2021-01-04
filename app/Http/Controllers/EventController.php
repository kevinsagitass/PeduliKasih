<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Event;
use App\Model\EventParticipant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function validateEventData($data)
    {
        $validator = Validator::make($data, [
            'event_name' => 'required|max:20',
            'event_desc' => 'required',
            'event_location' => 'required',
            'event_start_date' => 'required|date|after_or_equal:today',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'event_organizer' => 'required',            
        ]);
        return $validator;
    }

    public function addEvent(Request $request)
    {
        $param = $request->all();
        if(!isset($param['event_max_participant']) || !isset($param['participant_limited'])) {
            $param['event_max_participant'] = 0;
        }
        
        $validator = $this->validateEventData($param);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $new_name = null;
        $event = new Event();
        $event->event_name = $param['event_name'];
        $event->event_desc = $param['event_desc'];
        $event->event_location = $param['event_location'];
        $event->event_start_date = $param['event_start_date'];
        $event->event_end_date = $param['event_end_date'];
        $event->event_organizer = $param['event_organizer'];
        $event->user_id = Auth::User()->id;
        if(isset($param['event_max_participant'])) {
            $event->event_max_participant = $param['event_max_participant'];
        } else {
            $event->event_max_participant = 0;
        }
        if(isset($param['image'])) {
            $image = $request->file('image');
            $event->picture = rand() . '.' . $param['image']->getClientOriginalExtension();
            $new_name = $event->picture;

            if (!File::exists(public_path() . '\EventPosters')) {
                File::makeDirectory(public_path() . '\EventPosters', 0777, true, true);
            }
            $image->move(public_path() . '\EventPosters', $new_name);
        }
        $event->save();

        return redirect('home');
    }

    public function eventDetails($eventId)
    {
        $event = Event::query()->where('event_id', '=', $eventId)->firstOrFail();

        $userJoined = false;

        $participants = EventParticipant::query()
        ->join('users', function ($query){
            $query->on('users.id', '=', 'event_participant.user_id');
        })->where('event_id', '=', $eventId)->get();

        if(!$event) {
            abort(404);
        }

        foreach($participants as &$participant) {
            if($participant->user_id == Auth::User()->id) {
                $userJoined = true;
            }
            $participant->joined_date = Carbon::parse($participant->joined_date)->toDateTimeString();
        }

        return view('event_detail', ['event' => $event, 'participants' => $participants, 'userJoined' => $userJoined]);
    }

    public function joinEvent($eventId, $userId)
    {
        $event = Event::query()->where('event_id', '=', $eventId)->firstOrFail();

        if($userId != Auth::User()->id) {
            abort(403);
        }

        if(EventParticipant::query()->where('event_id', '=', $eventId)->where('user_id', '=', $userId)->first() != null) {
            return redirect()->back()->withErrors(['You Have Already Joined This Event!'])->withInput();
        }

        if($event->event_max_participant != 0) {
            $currentParticipants = EventParticipant::query()->where('event_id', '=', $eventId)->get();

            if(count($currentParticipants) < $event->event_max_participant) {
                $newParticipant = new EventParticipant();

                $newParticipant->event_id = $eventId;
                $newParticipant->user_id = $userId;
                $newParticipant->joined_date = Carbon::now();

                $newParticipant->save();
            }else{
                return redirect()->back()->withErrors(['Event Participants Already Full'])->withInput();
            }
        } else {
                $newParticipant = new EventParticipant();

                $newParticipant->event_id = $eventId;
                $newParticipant->user_id = $userId;
                $newParticipant->joined_date = Carbon::now();

                $newParticipant->save();
        }

        return redirect('home');
    }

    public function unJoinEvent($eventId, $userId)
    {
        $event = Event::query()->where('event_id', '=', $eventId)->firstOrFail();

        if($userId != Auth::User()->id) {
            abort(403);
        }

        if(EventParticipant::query()->where('event_id', '=', $eventId)->where('user_id', '=', $userId)->firstOrFail() == null) {
            return redirect()->back()->withErrors(['You Are Not A Participant of This Event!'])->withInput();
        }

        $eventParticipant = EventParticipant::query()
        ->where('user_id', '=', $userId)
        ->where('event_id', '=', $eventId)
        ->delete();

        return redirect('home');
    }

    public function deleteEvent($eventId)
    {
        $event = Event::query()->where('event_id', '=', $eventId)->firstOrFail();

        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Promotor" || $event->user_id != Auth::User()->id){
            abort(403);
        }

        EventParticipant::query()
        ->where('event_id', '=', $eventId)
        ->delete();

        $event->delete();

        return redirect('home');
    }

    public function showUpdateEventForm($eventId)
    {
        $event = Event::query()->where('event_id', '=', $eventId)->firstOrFail();

        $event->event_start_date = date('Y-m-d\TH:i', strtotime($event->event_start_date));
        $event->event_end_date = date('Y-m-d\TH:i', strtotime($event->event_end_date));

        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Promotor" || $event->user_id != Auth::User()->id){
            abort(403);
        }

        return view('update_event', ['event' => $event]);
    }

    public function updateEvent(Request $request, $eventId)
    {
        $event = Event::query()->where('event_id', '=', $eventId)->firstOrFail();

        $param = $request->all();
        if(!isset($param['event_max_participant']) || !isset($param['participant_limited'])) {
            $param['event_max_participant'] = 0;
        }

        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Promotor" || $event->user_id != Auth::User()->id){
            abort(403);
        }

        $event->event_name = $param['event_name'];
        $event->event_desc = $param['event_desc'];
        $event->event_location = $param['event_location'];
        $event->event_start_date = $param['event_start_date'];
        $event->event_end_date = $param['event_end_date'];
        $event->event_organizer = $param['event_organizer'];
        if(isset($param['event_max_participant'])) {
            $event->event_max_participant = $param['event_max_participant'];
        } else {
            $event->event_max_participant = 0;
        }
        if(isset($param['image'])) {
            $image = $request->file('image');
            $event->picture = rand() . '.' . $param['image']->getClientOriginalExtension();
            $new_name = $event->picture;

            if (!File::exists(public_path() . '\EventPosters')) {
                File::makeDirectory(public_path() . '\EventPosters', 0777, true, true);
            }
            $image->move(public_path() . '\EventPosters', $new_name);
        }
        $event->save();

        return redirect('home');
    }
}
