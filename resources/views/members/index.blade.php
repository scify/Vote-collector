@extends('app')

@section('content')
    <h1>Members</h1>

    @if (count($members) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th colspan="3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr>
                            <td>
                                {{ $member->first_name }}
                            </td>
                            <td>
                                {{ $member->last_name }}
                            </td>
                            <td>
                                <!-- Info button -->
                                <a href="members/{{ $member->id }}" class="btn btn-default btn-xs">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </a>
                            </td>
                            <td>
                                <!-- Edit button -->
                                <a href="members/{{ $member->id }}/edit" class="btn btn-primary btn-xs">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </td>
                            <td>
                                {!! Form::open(['url' => 'members/' . $member->id]) !!}
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
            There are no members!
        </p>
    @endif

    <a href="members/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> New member</a>
@stop