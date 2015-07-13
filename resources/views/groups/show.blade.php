@extends('app')

@section('content')
    <h1>{{$group->name}}</h1>

    @if (count($members) > 0)
        <h2>Μέλη:</h2>

        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Όνομα</th>
                        <th>Επώνυμο</th>
                        <th>Ενέργειες</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <td>{{ $member->first_name }}</td>
                            <td>{{ $member->last_name }}</td>
                            <td>
                                <!-- Info button -->
                                <a href="/members/{{ $member->id }}" class="btn btn-default btn-xs">
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
            Αυτή η κοινοβουλευτική ομάδα δεν έχει κανένα μέλος!
        </p>
    @endif

@stop