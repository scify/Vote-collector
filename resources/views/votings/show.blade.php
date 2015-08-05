@extends('app')

@section('head')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="{{ URL::asset('js/votingChart.js') }}"></script>
@stop

@section('content')
    <h1>Προβολή ψηφοφορίας</h1>

    <div class="jumbotron">
        <h1>{{ $voting->title }}</h1>

        <p>
            Αντικείμενα: {{ $voting->votingItems()->count() }}
        </p>
        @if($voting->completed)
            <p>
                <a class="btn btn-primary btn-lg" href="{{ url('votings/' . $voting->id . '/download') }}" role="button"><span class="glyphicon glyphicon-cloud-download"></span> Κατέβασμα αποτελεσμάτων</a>
            </p>
        @endif
    </div>

    {{-- If the voting is completed, show the votes --}}
    @if($voting->completed)
        {{-- Google Chart --}}
        <div id="piechart" style="width: 900px; height: 500px; display: block; margin: 0 auto;"></div>

        {{-- Table with all the votes --}}
        <div class="panel panel-default">
            <div class="panel-heading">Αποτελέσματα</div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>Ονοματεπώνυμο</th>
                        <th>Ψήφος</th>
                    </thead>
                    <tbody>
                        @foreach($memberVotes as $mv)
                            <tr>
                                <td>
                                    {{ $mv['member'] }}
                                </td>
                                <td>
                                    {{ $mv['answer'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop