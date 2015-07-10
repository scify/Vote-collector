@extends('app')

@section('content')
    <h1>Showing member</h1>

    <ul class="nav nav-tabs">
      <li role="presentation" class="active"><a href="#info" data-toggle="tab">Info</a></li>
      <li role="presentation"><a href="#groups" data-toggle="tab">Groups</a></li>
    </ul>

    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <div class="tab-content">
                <h2>{{ $member->first_name }} {{ $member->last_name }}</h2>

                <p>
                    Member of {{ $groups->count() }} group{{ ($groups->count() == 1)?'':'s' }}.
                </p>
            </div>
        </div>
        <div id="groups" class="tab-pane fade">
            @if(count($groups) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td>
                                        {{ $group->name }}
                                    </td>
                                    <td>
                                        <!-- Info button -->
                                        <a href="/groups/{{ $group->id }}" class="btn btn-default btn-xs">
                                            <span class="glyphicon glyphicon-info-sign"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>
                    This member does not belong to any groups.
                </p>
            @endif
        </div>
    </div>



    <a href="/members" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
@stop