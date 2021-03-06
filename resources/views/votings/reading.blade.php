@extends('app')

@section('head')
    <link rel="stylesheet" href="{{ URL::asset('css/readings.css') }}">
@stop

@section('content')
    {{-- Shortcuts box --}}
    <div class="shortcutsDiv pull-right">
        <div class="panel panel-info">
            <div class="panel-heading">Συντομεύσεις <a href="#" id="closeShortcutsLink" class="btn btn-xs pull-right">&times;</a></div>
            <div class="panel-body">
                <kbd>W</kbd> <span class="glyphicon glyphicon-arrow-right"></span> <span class="label label-default">Προηγούμενος</span><br>
                <kbd>A</kbd> <span class="glyphicon glyphicon-arrow-right"></span> <span class="label label-warning">Απουσιάζει</span> / <span class="label label-success">Δεν απουσιάζει</span><br>
                <kbd>S</kbd> <span class="glyphicon glyphicon-arrow-right"></span> <span class="label label-primary">Επόμενος</span>
            </div>
        </div>
    </div>

    {{-- Page header with voting title --}}
    <h1 id="title" class="page-header">Πρώτη ανάγνωση <small>{{ $votingTitle }}</small></h1>

    {{-- Table with members and checkboxes etc. --}}
    <div id="votesDiv" class="table-responsive" data-votingid="{{ $votingid }}">
        <table class="table">
            <thead>
                <th></th>
                @foreach($myVotingItems as $vi)
                    <th>{{ $vi['title'] }}</th>
                @endforeach
                <th></th>
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

    <div id="readingsButtonGroup" class="btn-group">
        <a href="#" id="nextPhaseBtn" class="btn btn-primary"><span class="glyphicon glyphicon-forward"></span> Δεύτερη ανάγνωση</a>
    </div>
@stop

@section('footer')
    {{-- Scripts for keeping the table header visible when scrolling --}}
    <script src="{{ URL::asset('js/jquery.stickytableheaders.min.js') }}"></script>
    <script>
        $(function() {
            $('table').stickyTableHeaders();
        });
    </script>

    {{-- Scripts to make the reading work --}}
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