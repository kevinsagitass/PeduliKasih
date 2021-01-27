<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Event;
use App\Model\EventParticipant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $onGoingEvents = Event::query()
        ->where('event_end_date', '>=', Carbon::now())
        ->orderBy('created_at', 'DESC')
        ->paginate(5);


        $pastEvents = Event::query()
        ->where('event_end_date', '<', Carbon::now())
        ->orderBy('created_at', 'DESC')
        ->paginate(5);

        return view('home', ['events' => $onGoingEvents, 'pastEvents' => $pastEvents]);
    }

    public function searchEvent(Request $request)
    {
        $q = $request->only(['q']);
        if($q != []) {
            $q = $q['q'];

            if(!empty($q)) {
                $events = Event::query()
                ->where(function(Builder $query) use ($q) {
                    return $query->where('event_name','LIKE','%'.$q.'%')
                    ->orWhere('event_start_date', 'LIKE', '%'.$q.'%')
                    ->orWhere('event_end_date', 'LIKE', '%'.$q.'%')
                    ->orWhere('event_organizer', 'LIKE', '%'.$q.'%');
                })
                ->where('event_end_date', '>=', Carbon::now())
                ->orderBy('created_at', 'DESC')
                ->paginate(5);

                $pastEvents = Event::query()
                ->where(function(Builder $query) use ($q) {
                    return $query->where('event_name','LIKE','%'.$q.'%')
                    ->orWhere('event_start_date', 'LIKE', '%'.$q.'%')
                    ->orWhere('event_end_date', 'LIKE', '%'.$q.'%')
                    ->orWhere('event_organizer', 'LIKE', '%'.$q.'%');
                })
                ->where('event_end_date', '<', Carbon::now())
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
            }else{
                $events = Event::query()
                ->where('event_end_date', '>=', Carbon::now())
                ->orderBy('created_at', 'DESC')
                ->paginate(5);

                $pastEvents = Event::query()
                ->where('event_end_date', '<', Carbon::now())
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
            }
            

            return view('home')->with(['events' => $events, 'notFound' => true, 'pastEvents' => $pastEvents]);
        } else {
            $events = Event::query()
            ->where('event_end_date', '>=', Carbon::now())
            ->orderBy('created_at', 'DESC')
            ->paginate(5);

            $pastEvents = Event::query()
            ->where('event_end_date', '<', Carbon::now())
            ->orderBy('created_at', 'DESC')
            ->paginate(5);

            return view('home')->with(['events' => $events, 'pastEvents' => $pastEvents]);
        }
    }

    public function showAddEventForm()
    {
        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Promotor") {
            abort(403);
        }

        return view('add_event');

    }

    public function showManagedEvents()
    {
        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Promotor"){
            abort(403);
        }

        $events = Event::query()->where('user_id', '=', Auth::User()->id)->paginate(5);

        return view('managed_events', ['events' => $events]);
    }

    public function searchManagedEvent(Request $request)
    {
        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Promotor"){
            abort(403);
        }

        $q = $request->only(['q']);
        if($q != []) {
            $q = $q['q'];
            $events = Event::query()->where('event_name','LIKE','%'.$q.'%')
            ->orWhere('event_start_date', 'LIKE', '%'.$q.'%')
            ->orWhere('event_end_date', 'LIKE', '%'.$q.'%')
            ->orWhere('event_organizer', 'LIKE', '%'.$q.'%')
            ->where('user_id', '=', Auth::User()->id)
            ->paginate(5);

            return view('managed_events')->with(['events' => $events, 'notFound' => true]);
        } else {
            $events = Event::query()
            ->where('user_id', '=', Auth::User()->id)
            ->paginate(5);

            return view('managed_events')->with(['events' => $events]);
        }

    }

    public function showJoinedEvents()
    {
        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Volunteer"){
            abort(403);
        }

        $events = EventParticipant::query()
        ->join('event', function ($query){
            $query->on('event.event_id', '=', 'event_participant.event_id');
        })
        ->where('event_participant.user_id', '=', Auth::User()->id)->paginate(5);

        return view('joined_events', ['events' => $events]);
    }

    public function searchJoinedEvent(Request $request)
    {
        if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName != "Volunteer"){
            abort(403);
        }

        $q = $request->only(['q']);
        if($q != []) {
            $q = $q['q'];
            $events = EventParticipant::query()
            ->join('event', function ($query){
                $query->on('event.event_id', '=', 'event_participant.event_id');
            })
            ->where('event_name','LIKE','%'.$q.'%')
            ->orWhere('event_start_date', 'LIKE', '%'.$q.'%')
            ->orWhere('event_end_date', 'LIKE', '%'.$q.'%')
            ->orWhere('event_organizer', 'LIKE', '%'.$q.'%')
            ->where('event_participant.user_id', '=', Auth::User()->id)->paginate(5);

            return view('joined_events')->with(['events' => $events, 'notFound' => true]);
        } else {
            $events = EventParticipant::query()
            ->join('event', function ($query){
                $query->on('event.event_id', '=', 'event_participant.event_id');
            })
            ->where('event_participant.user_id', '=', Auth::User()->id)->paginate(5);

            return view('joined_events')->with(['events' => $events]);
        }

    }
}
