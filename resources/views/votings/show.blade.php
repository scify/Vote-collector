@extends('app')

@section('content')
    <h1>Προβολή ψηφοφορίας</h1>

    <div class="jumbotron">
        <h1>{{ $voting->title }}</h1>

        <p>
            Αντικείμενα: {{ $voting->votingItems()->count() }}
        </p>
        @if($voting->completed)
            <p>
                <a class="btn btn-primary btn-lg" href="{{ url('votings/' . $voting->id . '/download') }}" role="button"><span class="glyphicon glyphicon-export"></span> Εξαγωγή αποτελεσμάτων</a>
            </p>
        @endif
    </div>

    {{-- If the voting is completed, show the votes --}}
    @if($voting->completed)
        <div class="panel panel-default">
            <div class="panel-heading">Αποτελέσματα</div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>Ονοματεπώνυμο</th>
                        @foreach($myVotingItems as $votingItem)
                            <th>{{ $votingItem['title'] }}</th>
                        @endforeach
                    </thead>
                    <tbody>
                        @foreach($memberVotes as $mv)
                            <tr>
                                <td>
                                    {{ $mv['fullname'] }}
                                </td>
                                @foreach($myVotingItems as $votingItem)
                                    <td>
                                        {{ $mv[$votingItem['id']] or 'Απών' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop