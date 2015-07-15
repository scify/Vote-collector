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
    </div>


    {{-- Check if the voting has any votes, and show them --}}
    @if(count($memberVotes) > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <th>Ονοματεπώνυμο</th>
                    <th>Επιλογή</th>
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
    @endif
@stop