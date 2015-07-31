@extends('app')

@section('head')
    <link rel="stylesheet" href="{{ URL::asset('css/readings.css') }}">
@stop

@section('content')
    <h1 id="title" class="page-header">Πρώτη ανάγνωση</h1>

    {!! Form::open(['action' => 'VotingsController@saveAnswers', 'class' => 'form-horizontal', 'id' => 'votesform', 'data-votingid' => $votingid]) !!}
        @foreach($myMembers as $member)
            <div class="form-group member col-sm-12" data-saved="false" data-id="{{ $member['id'] }}">
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

                <span id="selAnswerLabel{{ $member['id'] }}" class="label label-primary pull-left"></span>
            </div>
        @endforeach

        <a href="{{ url('votings') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Πίσω</a>
        <a href="#" id="nextPhaseBtn" class="btn btn-primary"><span class="glyphicon glyphicon-forward"></span> Δεύτερη ανάγνωση</a>
    {!! Form::close() !!}
@stop

@section('footer')
    <script>
        var submitVotesUrl = '{{ url('votings/reading') }}';    {{-- URL for submitting votes, using url() --}}
        var votingUrl = '{{ url('votings/' . $votingid) }}';    {{-- URL for going to the voting page, used by the alert shown after voting is complete --}}
        var deleteVoteUrl = '{{ url('votings/reading/dv') }}';  {{-- URL for deleting a member's vote after they're marked as absent --}}
    </script>
    <script src="{{ URL::asset('js/readings.js') }}"></script>
@stop