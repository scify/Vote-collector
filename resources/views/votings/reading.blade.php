@extends('app')

@section('head')
    <link rel="stylesheet" href="{{ URL::asset('css/readings.css') }}">
@stop

@section('content')
    <h1 id="title" class="page-header">Πρώτη ανάγνωση</h1>

    {!! Form::open(['action' => 'VotingsController@saveAnswers', 'class' => 'form-horizontal', 'id' => 'votesform', 'data-votingid' => $votingid]) !!}
        @foreach($myMembers as $member)

                <!--todo: data-changed is useless, use it to save data if needed when switching current members by clicking their names-->

            <div class="form-group member col-sm-12" data-saved="false" data-changed="false" data-id="{{ $member['id'] }}">
                <span class="memberName pull-left col-sm-3">
                    {{ $member['full_name'] }}
                </span>
                <div class="radios col-sm-3 hidden">
                    @foreach($myAnswers as $answer)
                        {!! Form::radio('answer_' . $member['id'], $answer['id'], $member['groupAnswerId'] == $answer['id'], ['id' => 'rd' . $member['id'] . $answer['id']]) !!}
                        {!! Form::label('rd' . $member['id'] . $answer['id'], $answer['answer'], ['class' => 'control-label']) !!}
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