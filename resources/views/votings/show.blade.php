@extends('app')

@section('content')
    <h1>Προβολή ψηφοφορίας</h1>

    <div class="jumbotron">
        <h1>{{ $voting->title }}</h1>

        <p>
            Τύπος: {{ $type->title }}
        </p>
        <p>
            Αντικείμενο: {{ $objective->title }}
        </p>
        @if(count($memberVotes) > 0)
            <p>
                <a class="btn btn-primary btn-lg" href="#" role="button"><span class="glyphicon glyphicon-cloud-download"></span> Κατέβασμα αποτελεσμάτων</a>
            </p>
        @endif
    </div>


    {{-- Check if the voting has any votes, and show them --}}
    @if(count($memberVotes) > 0)
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