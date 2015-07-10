@extends('app')

@section('content')
    <h1>Vote Objectives</h1>

    @if (count($voteObjectives) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <th>Title</th>
                    <th>Description</th>
                    <th colspan="3">Actions</th>
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
                                {!! Form::open(['url' => 'voteobjectives/' . $vo->id]) !!}
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
            There are no vote objectives!
        </p>
    @endif

    <a href="voteobjectives/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> New objective</a>
@stop