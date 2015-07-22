@extends('app')

@section('head')
    <link rel="stylesheet" src="{{ URL::asset('css/readings.css') }}">
@stop

@section('content')
    <h1 id="title">Πρώτη ανάγνωση</h1>

    {!! Form::open(['action' => 'VotingsController@saveAnswers', 'class' => 'form-horizontal', 'id' => 'votesform', 'data-votingid' => $voting->id]) !!}
        @foreach($members as $member)
            <fieldset>
                <div class="form-group member" data-status="not_voted" data-id="{{ $member->id }}">
                    {!! Form::label('answer_' . $member->id, $member->first_name . ' ' . $member->last_name, ['class' => 'currentMember control-label col-sm-2']) !!}
                    <div class="col-sm-3">
                        {!! Form::select('answer_' . $member->id, $voting->type->answers->lists('answer', 'id'), $member->groupAnswer($voting->id), ['class' => 'form-control selectpicker']) !!}
                    </div>
                </div>
            </fieldset>
        @endforeach

        {{--{!! Form::submit('Αποθήκευση', ['class' => 'btn btn-primary']) !!}--}}
        <a href="/votings" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Πίσω</a>
    {!! Form::close() !!}
@stop

@section('footer')
    <script src="{{ URL::asset('js/readings.js') }}"></script>
@stop