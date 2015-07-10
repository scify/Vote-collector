@extends('app')

@section('content')
    <h1>Vote Types</h1>

    @if (count($voteTypes) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <th>Type</th>
                    <th colspan="3">Actions</th>
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
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>
            There are no vote types!
        </p>
    @endif

    <a href="votetypes/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> New vote type</a>
@stop