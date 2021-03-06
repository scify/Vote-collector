@extends('app')

@section('content')
    <h1>Ψηφοφορίες</h1>

    @if(count($votings) > 0)
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Τίτλος</th>
                        <th>Αντικείμενα</th>
                        <th colspan="3">Ενέργειες</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($votings as $voting)
                        <tr>
                            <td class="col-lg-3">
                                {{ $voting->title }}
                            </td>
                            <td class="col-lg-2 text-nowrap">
                                {{ $voting->votingItems()->count() }} αντικείμεν{{ ($voting->votingItems()->count() == 1)?'ο':'α' }}
                            </td>
                            <td class="col-lg-1"> {{-- Info button --}}
                                @include('partials.infoBtn', ['url' => 'votings/' . $voting->id])
                            </td>
                            <td class="col-lg-3">
                                @if( $voting->votingItems()->count() == 0)
                                    <span class="label label-danger">
                                        Δεν υπάρχουν αντικείμενα ψηφοφορίας
                                    </span>
                                @elseif($voting->completed)
                                    <span class="label label-info">
                                        Η ψηφοφορία ολοκληρώθηκε
                                    </span>
                                @elseif( $voting->votes->count() > 0 )
                                    <a href="votings/reading/{{ $voting->id }}" class="btn btn-success btn-xs">
                                        <span class="glyphicon glyphicon-play"></span> Συνέχιση
                                    </a>
                                @elseif( $voting->defaultVotesSet() )
                                    <div class="text-nowrap">
                                        <a href="votings/reading/{{ $voting->id }}" class="btn btn-success btn-xs">
                                            <span class="glyphicon glyphicon-book"></span> Εκκίνηση
                                        </a>
                                        <a href="votings/answers/{{ $voting->id }}/edit" class="btn btn-primary btn-xs">
                                            <span class="glyphicon glyphicon-pencil"></span> Αλλαγή προεπιλεγμένων απαντήσεων
                                        </a>
                                    </div>
                                @else
                                    <a href="votings/answers/{{ $voting->id }}" class="btn btn-primary btn-xs">
                                        <span class="glyphicon glyphicon-file"></span> Επιλογή προεπιλεγμένων απαντήσεων
                                    </a>
                                @endif
                            </td>
                            <td class="col-lg-1">
                                @include('partials.deleteBtn', ['url' => 'votings', 'id' => $voting->id])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>
            Δεν υπάρχουν ψηφοφορίες!
        </p>
    @endif

    <a href="votings/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Νέα ψηφοφορία</a>
@stop