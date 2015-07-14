@extends('app')

@section('content')
    <h1>Αντικείμενα ψηφοφορίας</h1>

    @if (count($voteObjectives) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <th>Τίτλος</th>
                    <th>Περιγραφή</th>
                    <th colspan="3">Ενέργειες</th>
                </thead>
                <tbody>
                    @foreach($voteObjectives as $vo)
                        <tr>
                            <td>
                                {{ $vo->title }}
                            </td>
                            <td>
                                <p>
                                    {{ substr($vo->description, 0, 25) }}{{ (strlen($vo->description) > 25)?'...':'' }}
                                </p>
                            </td>
                            <td>
                                <!-- Info button -->
                                <a href="voteobjectives/{{ $vo->id }}" class="btn btn-default btn-xs">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </a>
                            </td>
                            <td>
                                <!-- Edit button -->
                                <a href="voteobjectives/{{ $vo->id }}/edit" class="btn btn-primary btn-xs">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </td>
                            <td>
                                @include('partials.deleteBtn', ['url' => 'voteobjectives', 'id' => $vo->id])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>
            Δεν υπάρχουν αντικείμενα ψηφοφορίας!
        </p>
    @endif

    <a href="voteobjectives/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Νέο αντικείμενο</a>
@stop