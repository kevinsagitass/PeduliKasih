@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="box-shadow: 10px 10px grey;">
                <div class="card-header" style="color: white; background-color: lightgrey; text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;">{{ __('Add Event') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('add-event') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="event_name" class="col-md-4 col-form-label text-md-right">{{ __('Event Name') }}</label>

                            <div class="col-md-6">
                                <input id="event_name" type="text" class="form-control @error('event_name') is-invalid @enderror" name="event_name" value="{{ old('event_name') }}" required autocomplete="event_name" autofocus>

                                @error('event_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="event_desc" class="col-md-4 col-form-label text-md-right">{{ __('Event Description') }}</label>

                            <div class="col-md-6">
                                <input id="event_desc" type="text" class="form-control @error('event_desc') is-invalid @enderror" name="event_desc" value="{{ old('event_desc') }}" required autocomplete="event_desc">

                                @error('event_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="event_location" class="col-md-4 col-form-label text-md-right">{{ __('Event Location') }}</label>

                            <div class="col-md-6">
                                <input id="event_location" type="text" class="form-control @error('event_location') is-invalid @enderror" name="event_location" value="{{ old('event_location') }}" required autocomplete="event_location">

                                @error('event_location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="event_start_date" class="col-md-4 col-form-label text-md-right">{{ __('Event Start Date') }}</label>

                            <div class="col-md-6">
                                <input class="date form-control" type="datetime-local" id="event_start_date" name="event_start_date" value="{{ old('event_start_date') }}">

                                @error('event_start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="event_end_date" class="col-md-4 col-form-label text-md-right">{{ __('Event End Date') }}</label>

                            <div class="col-md-6">
                                <input class="date form-control" type="datetime-local" id="event_end_date" name="event_end_date" value="{{ old('event_end_date') }}">

                                @error('event_end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="event_organizer" class="col-md-4 col-form-label text-md-right">{{ __('Event Organizer') }}</label>

                            <div class="col-md-6">
                                <input id="event_organizer" type="text" class="form-control @error('event_organizer') is-invalid @enderror" name="event_organizer" value="{{ old('event_organizer') }}" required autocomplete="event_organizer">

                                @error('event_organizer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Event Participant Limited</label>
                            <label class="switch"  style="margin-left: 12px">
                                <input name="participant_limited" value="true" id="max_participant" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="form-group row" id="max_participant_form">
                            <label for="event_max_participant" class="col-md-4 col-form-label text-md-right">{{ __('Event Max participant') }}</label>

                            <div class="col-md-6">
                                <input id="event_max_participant" type="number" class="form-control @error('event_max_participant') is-invalid @enderror" name="event_max_participant" value="{{ old('event_max_participant') }}">

                                @error('event_max_participant')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="image" class="col-md-4 col-form-label text-md-right">Event Poster</label>

                                <div class="col-md-6">
                                    <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Event') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#max_participant_form').hide();
            $("#max_participant").click(function(){
                if($("#max_participant_form").is(':visible')) {
                    $("#max_participant_form").hide();
                }else{
                    $("#max_participant_form").show();
                }
            });
        });
    </script>
@endpush
@endsection
