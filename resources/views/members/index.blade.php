@extends('app')

@section('content')
    <h1>Βουλευτές</h1>

    @foreach($districts as $district)
        @if($district->members->count() > 0)
            <div class="panel panel-default">
                <div class="panel-heading">{{ $district->name }}</div>

                <div class="table-responsive">
                    <table id="members_list" class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Σειρά</th>
                                <th>Όνομα</th>
                                <th>Επώνυμο</th>
                                <th colspan="3">Ενέργειες</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($district->members()->orderBy('order')->get() as $member)
                                <tr class="member" data-id="{{ $member->id }}">
                                    <td class="priority">
                                        {{ $member->order }}
                                    </td>
                                    <td>
                                        {{ $member->first_name }}
                                    </td>
                                    <td>
                                        {{ $member->last_name }}
                                    </td>
                                    <td> {{-- Info button --}}
                                        @include('partials.infoBtn', ['url' => 'members/' . $member->id])
                                    </td>
                                    <td> {{-- Edit button --}}
                                        @include('partials.editBtn', ['url' => 'members/' . $member->id . '/edit'])
                                    </td>
                                    <td> {{-- Delete button --}}
                                        @include('partials.deleteBtn', ['url' => 'members', 'id' => $member->id])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    <a href="members/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Νέος βουλευτής</a>
@stop

@section('footer')
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ URL::asset('js/members.js') }}"></script>
@stop