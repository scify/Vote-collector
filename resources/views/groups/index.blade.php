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
                            <td class="col-sm-1"> {{-- Info button --}}
                                @include('partials.infoBtn', ['url' => 'groups/' . $group->id])
                            </td>
                            <td class="col-sm-1"> {{-- Edit button --}}
                                @include('partials.editBtn', ['url' => 'groups/' . $group->id . '/edit'])
                            </td>
                            <td class="col-sm-2"> {{-- Delete button --}}
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