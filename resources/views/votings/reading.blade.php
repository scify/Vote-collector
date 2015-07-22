@extends('app')

@section('head')
    <link rel="stylesheet" href="{{ URL::asset('css/readings.css') }}">
@stop

@section('content')
    <h1 id="title">Πρώτη ανάγνωση</h1>

    {!! Form::open(['action' => 'VotingsController@saveAnswers', 'class' => 'form-horizontal', 'id' => 'votesform', 'data-votingid' => $voting->id]) !!}
        @foreach($members as $member)
            <div class="form-group member" data-status="voted" data-id="{{ $member->id }}">
                {!! Form::label('answer_' . $member->id, $member->first_name . ' ' . $member->last_name, ['class' => 'control-label col-sm-2']) !!}
                <div class="radios col-sm-3">
                    @foreach($voting->type->answers as $answer)
                        {!! Form::radio('answer_' . $member->id, $answer->id, ($member->groupAnswer($voting->id) == $answer->id)) !!} {{ $answer->answer }} <br/>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{--{!! Form::submit('Αποθήκευση', ['class' => 'btn btn-primary']) !!}--}}
        <a href="/votings" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Πίσω</a>
    {!! Form::close() !!}
@stop

@section('footer')
    <script src="{{ URL::asset('js/readings.js') }}"></script>
@stop