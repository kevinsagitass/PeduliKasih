@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row warning text-center">
        <div class="col-md-12">
            @if($errors->any())
                <h4 class="text-danger">{{$errors->first()}}</h4>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="box-shadow: 10px 10px grey;">
                <div class="card-header" style="color: white; background-color: lightgrey; text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;">{{ __('Event Details') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6" style="border-right: 2px solid black; padding-right: 0px; text-align: center">
                            @if($event->picture != null)
                                <img style="width: 400px" src="{{asset('EventPosters/'.$event->picture)}}" alt="{{$event->event_name}}">
                            @else
                                <div style="height: 400px; line-height: 400px; text-align: center;">
                                    <p style=" line-height: 1.5; display: inline-block; vertical-align: middle;">No Image</p></div>
                            @endif
                        </div>
                        <div class="col-md-6">
                           <table style="width: 100%; border: 1px solid black;">
                                <thead style="border: 1px solid black">
                                    <th colspan="2" style="text-align: center">Event Details</th>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid black; text-align: center">
                                        <td style="border-right: 1px solid black; padding: 10px">
                                            <strong>
                                                Event Name
                                            </strong>
                                        </td>
                                        <td style="padding: 10px">
                                            <strong>
                                                {{$event->event_name}}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black; text-align: center">
                                        <td style="border-right: 1px solid black; padding: 10px">
                                            <strong>
                                                Event Description
                                            </strong>
                                        </td>
                                        <td style="padding: 10px">
                                            <strong>
                                                {{$event->event_desc}}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black; text-align: center">
                                        <td style="border-right: 1px solid black; padding: 10px">
                                            <strong>
                                                Event Location
                                            </strong>
                                        </td>
                                        <td style="padding: 10px">
                                            <strong>
                                                {{$event->event_location}}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black; text-align: center">
                                        <td style="border-right: 1px solid black; padding: 10px">
                                            <strong>
                                                Event Start Date
                                            </strong>
                                        </td>
                                        <td style="padding: 10px">
                                            <strong>
                                                {{$event->event_start_date}}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black; text-align: center">
                                        <td style="border-right: 1px solid black; padding: 10px">
                                            <strong>
                                                Event End Date
                                            </strong>
                                        </td>
                                        <td style="padding: 10px">
                                            <strong>
                                                {{$event->event_end_date}}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black; text-align: center">
                                        <td style="border-right: 1px solid black; padding: 10px">
                                            <strong>
                                                Event Organizer
                                            </strong>
                                        </td>
                                        <td style="padding: 10px">
                                            <strong>
                                                {{$event->event_organizer}}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black; text-align: center">
                                        <td style="border-right: 1px solid black; padding: 10px">
                                            <strong>
                                                Event Max Participant
                                            </strong>
                                        </td>
                                        <td style="padding: 10px">
                                            <strong>
                                                @if($event->event_max_participant == 0)
                                                    No Limit
                                                @else
                                                    {{$event->event_max_participant}}
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                           </table>
                           <div class="row" style="margin-top: 5%">
                                <div class="col-md-4 text-center">
                                    @if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName == "Promotor" && $event->user_id == Auth::User()->id && $event->event_end_date >= $today)
                                        <a href="{{url('/update-event/'.$event->event_id)}}"><button style="width: 100%" class="btn btn-primary">Edit Event</button></a>
                                    @endif
                                </div>
                                <div class="col-md-4 text-center">
                                    @if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName == "Volunteer")
                                        @if($userJoined && $event->event_end_date >= $today)
                                            <form class="row" action="{{'/unjoin-event/'.$event->event_id.'/user-id/'.Auth::User()->id}}" method="POST">
                                                @csrf
                                                <button style="width: 100%" class="btn btn-primary">Unjoin Event</button>
                                            </form>
                                        @elseif($event->event_end_date < $today)
                                            <span class="text-danger">This Event has Passed</span>
                                        @elseif(!$userJoined && $event->event_end_date >= $today)
                                            <form class="row" action="{{'/join-event/'.$event->event_id.'/user-id/'.Auth::User()->id}}" method="POST">
                                                @csrf
                                                <button style="width: 100%" class="btn btn-primary">Join Event</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-4 text-center">
                                    @if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName == "Promotor" && $event->user_id == Auth::User()->id)
                                        <form class="row" action="{{'/delete-event/'.$event->event_id}}" method="POST">
                                            @csrf
                                            <button style="width: 100%" type="submit" class="btn btn-primary">Delete Event</button>
                                        </form>
                                    @endif
                                </div>
                           </div>
                           <div class="row" style="padding-top: 5%">
                                <div class="col-md-12">
                                    @if(count($participants) > 0)
                                        <h3>Joined Participants</h3>
                                        <div id="table-wrapper">
                                            <div id="table-scroll">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th><span class="text">Name</span></th>
                                                        <th><span class="text">Joined Date</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($participants as $participant)
                                                        <tr> <td>{{$participant->name}}</td> <td>{{$participant->joined_date}}</td> </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    @else
                                        <h3>Be The First to Join This Event!</h3>
                                    @endif
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection