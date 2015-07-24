@extends('app')

@section('content')
    <h1>{{$group->name}}</h1>

    @if (count($members) > 0)
        <h2>Μέλη: {{ count($members) }}</h2>

        <div class="panel panel-default">
            <div class="panel-heading">Ονόματα μελών</div>

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
                                <td> {{-- Info button --}}
                                    @include('partials.infoBtn', ['url' => '/members/' . $member->id])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <p>
            Αυτή η κοινοβουλευτική ομάδα δεν έχει κανένα μέλος!
        </p>
    @endif
@stop