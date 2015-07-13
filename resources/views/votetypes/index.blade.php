@extends('app')

@section('content')
    <h1>Τύποι ψηφοφορίας</h1>

    @if (count($voteTypes) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <th>Τύπος</th>
                    <th colspan="3">Ενέργειες</th>
                </thead>
                <tbody>
                    @foreach($voteTypes as $vt)
                        <tr>
                            <td>
                                {{ $vt->title }}
                            </td>
                            <td>
                                <!-- Info button -->
                                <a href="votetypes/{{ $vt->id }}" class="btn btn-default btn-xs">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </a>
                            </td>
                            <td>
                                <!-- Edit button -->
                                <a href="votetypes/{{ $vt->id }}/edit" class="btn btn-primary btn-xs">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </td>
                            <td>
                                <!-- ????? http://blog.elenakolevska.com/restful-deleting-in-laravel/ ?????? -->
                                {!! Form::open(['url' => 'votetypes/' . $vt->id]) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit('Διαγραφή', ['class' => 'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>
            Δεν υπάρχουν τύποι ψηφοφορίας!
        </p>
    @endif

    <a href="votetypes/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Νέος τύπος</a>
@stop