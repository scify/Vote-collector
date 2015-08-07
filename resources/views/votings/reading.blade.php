@extends('app')

@section('head')
    <link rel="stylesheet" href="{{ URL::asset('css/readings.css') }}">
@stop

@section('content')
    <h1 id="title" class="page-header">Πρώτη ανάγνωση <small>{{ $votingTitle }}</small></h1>

    <div id="votesDiv" class="table-responsive" data-votingid="{{ $votingid }}">
        <table class="table">
            <thead>
                <th>Βουλευτής</th>
                @foreach($myVotingItems as $vi)
                    <th>{{ $vi['title'] }}</th>
                @endforeach
                <th>Ενέργειες</th>
            </thead>
            <tbody>
                @foreach($myMembers as $member)
                    <tr class="member" data-saved="{{ $member['isSaved'] }}" data-id="{{ $member['id'] }}" data-wassavedasabsent="{{ $member['isAbsent'] }}">
                        <td class="memberName col-sm-3">
                            {{ $member['full_name'] }}
                        </td>
                        @foreach($myVotingItems as $vi)
                            <td class="votingItem" data-id="{{ $vi['id'] }}">
                                <div class="radios hidden">
                                    @foreach($vi['answers'] as $answerId => $answer)
                                        <input type="radio" value="{{ $answerId }}" id="rd{{ $member['id'] . $vi['id'] . $answerId }}" name="answer_{{ $member['id'] . $vi['id'] }}" {{ ($member['answerIds'][$vi['id']] == $answerId) ? 'checked=checked' : '' }}>
                                        <label for="rd{{ $member['id'] . $vi['id'] . $answerId }}" class="control-label">{{ $answer }}</label>
                                        <br>
                                    @endforeach
                                </div>

                                {{-- Label that will display the selected answer --}}
                                <span id="selAnswerLabel{{ $member['id'] . $vi['id'] }}" class="label label-primary pull-left">{{ $member['labels'][$vi['id']] }}</span>
                            </td>
                        @endforeach
                        <td class="btnCell col-sm-3">
                            {{-- buttons or an absent label will be added here when/if needed by javascript --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ url('votings') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Πίσω</a>
    <div id="readingsButtonGroup" class="btn-group">
        <a href="#" id="nextPhaseBtn" class="btn btn-primary"><span class="glyphicon glyphicon-forward"></span> Δεύτερη ανάγνωση</a>
    </div>
@stop

@section('footer')
    <script>
        var submitVotesUrl = '{{ url('votings/reading') }}';                {{-- URL for submitting votes, using url() --}}
        var currentPageUrl = '{{ url('votings/reading/' . $votingid) }}';   {{-- URL of current page --}}
        var votingUrl = '{{ url('votings/' . $votingid) }}';                {{-- URL for going to the voting page, used by the alert shown after voting is complete --}}
        var deleteVoteUrl = '{{ url('votings/reading/dv') }}';              {{-- URL for deleting a member's vote after they're marked as absent --}}
        var markCompleteUrl = '{{ url('votings/complete/' . $votingid) }}'; {{-- URL for making the ajax request to mark this voting as completed --}}
    </script>
    <script src="{{ URL::asset('js/votings/readings/components.js') }}"></script>
    <script src="{{ URL::asset('js/votings/readings/readings.js') }}"></script>
@stop