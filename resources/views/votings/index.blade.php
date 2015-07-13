@extends('app')

@section('content')
    <h1>Votings</h1>

    @if(count($votings) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Objective</th>
                        <th colspan="2">Actions</th>
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
                                {!! Form::open(['url' => 'votings/' . $voting->id]) !!}
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
            There are no votings!
        </p>
    @endif

    <a href="votings/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> New voting</a>
@stop