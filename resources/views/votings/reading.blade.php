@extends('app')

@section('head')
    <link rel="stylesheet" href="{{ URL::asset('css/readings.css') }}">
@stop

@section('content')
    <h1 id="title">Πρώτη ανάγνωση</h1>

    {!! Form::open(['action' => 'VotingsController@saveAnswers', 'class' => 'form-horizontal', 'id' => 'votesform', 'data-votingid' => $voting->id]) !!}
        @foreach($members as $member)
            <div class="form-group member" data-status="voted" data-id="{{ $member->id }}">
                <span class="memberName pull-left col-sm-3">
                    {{ $member->first_name . ' ' . $member->last_name }}
                </span>
                <div class="radios col-sm-3">
                    @foreach($voting->type->answers as $answer)
                        @if ($member->groups->count() > 0)  {{-- If member is in a group, will select the group's answer by default --}}
                            {!! Form::radio('answer_' . $member->id, $answer->id, ($member->groupAnswer($voting->id) == $answer->id)) !!}
                        @else
                            {!! Form::radio('answer_' . $member->id, $answer->id, $voting->type->answers->first() == $answer) !!}
                        @endif

                        {!! Form::label('answer_' . $member->id, $answer->answer, ['class' => 'control-label']) !!}
                        <br/>
                    @endforeach
                </div>
            </div>
        @endforeach

        <a href="/votings" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Πίσω</a>
        <a href="#" id="nextPhaseBtn" class="btn btn-primary"><span class="glyphicon glyphicon-forward"></span> Δεύτερη ανάγνωση</a>
    {!! Form::close() !!}
@stop

@section('footer')
    <script src="{{ URL::asset('js/readings.js') }}"></script>
@stop