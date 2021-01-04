@extends('layouts.app')

@section('content')
<div class="container">
    @if(count($events) > 0 || isset($notFound))
    <div class="card" style="box-shadow: 10px 10px grey;">
        <div class="card-header text-center">
            <div class="col-md-12">
                <h1 class="title tableHeaderTitle">Managed Events</h1>
            </div>
        </div>
        <div class="card-body">
            <div class="row text-right">
                <div class="col-md-6">
                    <div>{{$events->links()}}</div>
                </div>
                <div class="col-md-6">
                    <form style="padding-bottom: 10px" action="/search-managed" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text" class="form-control" name="q"
                                placeholder="Search Event"> <span class="input-group-btn">
                                <button id="btnSearch" style="background-color: lightgrey" type="submit" class="btn btn-default">
                                    Search
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="responsive-table">
                        <li class="table-header">
                            <div class="col col-2 tableHeaderTitle">Event Poster</div>
                            <div class="col col-2 tableHeaderTitle">Event Name</div>
                            <div class="col col-2 tableHeaderTitle">Event Start Date</div>
                            <div class="col col-2 tableHeaderTitle">Event End Date</div>
                            <div class="col col-2 tableHeaderTitle">Event Organizer</div>
                        </li>
                        @foreach($events as $event)
                        <a href="{{url('/event-details/'.$event->event_id)}}" style="text-decoration: none; color: black">
                            <li class="table-row">
                                @if($event->picture != null)
                                    <div class="col col-2" data-label="Event Poster">
                                        <img style="width: 100px !important;" src="{{asset('EventPosters/'.$event->picture)}}" alt="{{$event->event_name}}">
                                    </div>
                                @else
                                    <div class="col col-2" data-label="Event Poster">No Image</div>
                                @endif
                                <div class="col col-2" data-label="Event Name">{{$event->event_name}}</div>
                                <div class="col col-2" data-label="Event Start Date">{{$event->event_start_date}}</div>
                                <div class="col col-2" data-label="Event End Date">{{$event->event_end_date}}</div>
                                <div class="col col-2" data-label="Event Organizer">{{$event->event_organizer}}</div>
                            </li>
                        </a>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-md-6">
                    <div>{{$events->links()}}</div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="row">
            <div class="col text-danger text-center">
                <h1 style="margin-top: 10%" class="text-danger">There is Currently No Managed Events :(</h1>
                @if(DB::table('roles')->where('id', '=', Auth::User()->role_id)->first()->roleName == "Promotor")
                    <a href="{{url('/add-event')}}"><button style="width: 200px" class="btn-primary">Add New Event Now!</button></a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection