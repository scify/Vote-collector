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
                        <th colspan="2">Ενέργειες</th>
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