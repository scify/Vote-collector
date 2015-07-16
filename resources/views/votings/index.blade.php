@extends('app')

@section('content')
    <h1>Ψηφοφορίες</h1>

    @if(count($votings) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Τίτλος</th>
                        <th>Τύπος</th>
                        <th>Αντικείμενο</th>
                        <th colspan="3">Ενέργειες</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($votings as $voting)
                        <tr>
                            <td>
                                {{ $voting->title }}
                            </td>
                            <td>
                                {{ $types[$voting->voting_type] }}
                            </td>
                            <td>
                                {{ $objectives[$voting->objective] }}
                            </td>
                            <td>
                                <!-- Info button -->
                                <a href="votings/{{ $voting->id }}" class="btn btn-default btn-xs">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </a>
                            </td>
                            <td>
                                @if( $voting->defaultVotesSet() )
                                    <a href="votings/reading/{{ $voting->id }}" class="btn btn-success btn-xs">
                                        <span class="glyphicon glyphicon-book"></span> Εκκίνηση
                                    </a>
                                @elseif ($voting->type->answers->count() > 0)
                                    <a href="votings/answers/{{ $voting->id }}" class="btn btn-primary btn-xs">
                                        <span class="glyphicon glyphicon-file"></span> Επιλογή απαντήσεων
                                    </a>
                                @else
                                    <span class="label label-warning">Δεν υπάρχουν απαντήσεις!</span>
                                @endif
                            </td>
                            <td>
                                @include('partials.deleteBtn', ['url' => 'votings', 'id' => $voting->id])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>
            Δεν υπάρχουν ψηφοφορίες!
        </p>
    @endif

    <a href="votings/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Νέα ψηφοφορία</a>
@stop