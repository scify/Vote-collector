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
                            <td> {{-- Info button --}}
                                @include('partials.infoBtn', ['url' => 'votetypes/' . $vt->id])
                            </td>
                            <td> {{-- Edit button --}}
                                @include('partials.editBtn', ['url' => 'votetypes/' . $vt->id . '/edit'])
                            </td>
                            <td> {{-- Delete button --}}
                                @include('partials.deleteBtn', ['url' => 'votetypes', 'id' => $vt->id])
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