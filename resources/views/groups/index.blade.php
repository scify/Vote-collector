@extends('app')

@section('content')
    <h1>Κοινοβουλευτικές Ομάδες</h1>

    @if(count($groups) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Όνομα</th>
                        <th colspan="3">Ενέργειες</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td>
                                {{ $group->name }}
                            </td>
                            <td>
                                <!-- Info button -->
                                <a href="groups/{{ $group->id }}" class="btn btn-default btn-xs">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </a>
                            </td>
                            <td>
                                <!-- Edit button -->
                                <a href="groups/{{ $group->id }}/edit" class="btn btn-primary btn-xs">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </td>
                            <td>
                                @include('partials.deleteBtn', ['url' => 'groups', 'id' => $group->id])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>
            Δεν υπάρχουν κοινοβουλευτικές ομάδες!
        </p>
    @endif

    <a href="groups/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Νέα ομάδα</a>

@stop